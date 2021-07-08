<?php

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