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

        $signer = new DkimSigner(
            config('mail.dkim_private_key'), 
            config('mail.dkim_domain'), 
            config('mail.dkim_selector'), 
            [],
            config('mail.dkim_passphrase')
        );
        $signedEmail = $signer->sign($message->getSymfonyMessage());
        $symfonyMessage->setHeaders($signedEmail->getHeaders());

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