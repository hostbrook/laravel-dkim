<?php

/**
 * HostBrook note:
 * The generic idea of the Message class code is taken from here:
 * https://github.com/vitalybaev/laravel-dkim
 * 
 * The original code has been changed to match Laravel versions ^7.4|^8.0 and easier configuration
 */

namespace HostBrook\LaravelDkim;

use Swift_Message;

class Mailer extends \Illuminate\Mail\Mailer
{
    protected function createMessage()
    {
        $message = new Message(new Swift_Message);

        if (! empty($this->from['address'])) {
            $message->from($this->from['address'], $this->from['name']);
        }

        if ( config('mail.dkim_selector') && config('mail.dkim_domain') && config('mail.dkim_private_key') ) 
        {
            $message->addDkim(
                config('mail.dkim_selector'), 
                config('mail.dkim_domain'), 
                config('mail.dkim_private_key')
            );
        }

        return $message;
    }

}