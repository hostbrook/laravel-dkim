<?php

/**
 * HostBrook note:
 * The generic idea of the Message class code is taken from here:
 * https://github.com/vitalybaev/laravel-dkim
 * 
 * The original code has been changed to match Laravel versions ^7.4|^8.0 and easier configuration
 */

namespace HostBrook\LaravelDkim;

use Swift_Signers_DKIMSigner;

class Message extends \Illuminate\Mail\Message
{

    public function addDkim($selector, $domain, $privateKey, $passphrase = '')
    {
        $signer = new Swift_Signers_DKIMSigner($privateKey, $domain, $selector, $passphrase);

        $signer->setHashAlgorithm('rsa-sha256');

        $this->swift->attachSigner($signer);

        return $this;
    }
}