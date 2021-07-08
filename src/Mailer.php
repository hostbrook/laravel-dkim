<?php

namespace HostBrook\LaravelDkim;

use Swift_Message;

class Mailer extends \Illuminate\Mail\Mailer
{
    protected function createMessage()
    {
        $message = new Message(new Swift_Message);

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