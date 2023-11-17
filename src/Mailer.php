<?php

/**
 * HostBrook note:
 * The generic idea of the Mailer class code is partipally based on the next sources:
 * https://github.com/simonschaufi/laravel-dkim
 * 
 * The original code has been changed to get DKIM data from the `mail.config` file
 */

namespace HostBrook\LaravelDkim;

use Illuminate\Contracts\Mail\Mailable as MailableContract;
use Illuminate\Mail\SentMessage;
use Symfony\Component\Mime\Crypto\DkimSigner;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class Mailer extends \Illuminate\Mail\Mailer
{
    /**
     * Send a new message using a view.
     *
     * @param MailableContract|string|array  $view
     * @param  array  $data
     * @param  \Closure|string|null  $callback
     * @return SentMessage|null
     */
    public function send($view, array $data = [], $callback = null): ?SentMessage
    {
        if ($view instanceof MailableContract) {
            return $this->sendMailable($view);
        }

        // First we need to parse the view, which could either be a string or an array
        // containing both an HTML and plain text versions of the view which should
        // be used when sending an e-mail. We will extract both of them out here.
        [$view, $plain, $raw] = $this->parseView($view);

        $data['message'] = $message = $this->createMessage();

        // Once we have retrieved the view content for the e-mail we will set the body
        // of this message using the HTML type, which will provide a simple wrapper
        // to creating view based emails that are able to receive arrays of data.
        if (! is_null($callback)) {
            $callback($message);
        }

        $this->addContent($message, $view, $plain, $raw, $data);

        // If a global "to" address has been set, we will set that address on the mail
        // message. This is primarily useful during local development in which each
        // message should be delivered into a single mail address for inspection.
        if (isset($this->to['address'])) {
            $this->setGlobalToAndRemoveCcAndBcc($message);
        }

        // Next we will determine if the message should be sent. We give the developer
        // one final chance to stop this message and then we will send it to all of
        // its recipients. We will then fire the sent event for the sent message.
        $symfonyMessage = $message->getSymfonyMessage();

        $privateKey = env('DKIM_PRIVATE_KEY') ? env('DKIM_PRIVATE_KEY','') : config('mail.dkim_private_key','');
        if (File::exists(base_path().$privateKey)) $privateKey = File::get(base_path().$privateKey);

        $domain = env('DKIM_DOMAIN') ? env('DKIM_DOMAIN','') : config('mail.dkim_domain','');
        $selector = env('DKIM_SELECTOR') ? env('DKIM_SELECTOR','') : config('mail.dkim_selector','');
        $passphrase = env('DKIM_PASSPHRASE') ? env('DKIM_PASSPHRASE','') : config('mail.dkim_passphrase','');

        // Sign emails if values of domain, selector and passphrase exist:
        if (!$privateKey) {
            Log::warning('The message hasn\'t been signed with DKIM: No private key set.');
        }
        elseif (!$domain) {
            Log::warning('The message hasn\'t been signed with DKIM: No domain set.');
        }
        elseif (!$selector) {
            Log::warning('The message hasn\'t been signed with DKIM: No selector set.');
        }
        else {
            $signer = new DkimSigner($privateKey, $domain, $selector, [], $passphrase);
            $signedEmail = $signer->sign($message->getSymfonyMessage());
            $symfonyMessage->setHeaders($signedEmail->getHeaders());
        }

        if ($this->shouldSendMessage($symfonyMessage, $data)) {
            $symfonySentMessage = $this->sendSymfonyMessage($symfonyMessage);

            if ($symfonySentMessage) {
                $sentMessage = new SentMessage($symfonySentMessage);

                $this->dispatchSentEvent($sentMessage, $data);

                return $sentMessage;
            }
        }
    }
}