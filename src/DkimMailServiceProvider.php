<?php

/**
 * HostBrook note:
 * The source code of the Service Provider is partipally based on the next sources:
 * https://github.com/DUDU54/laravel-dkim
 * https://github.com/simonschaufi/laravel-dkim
 */

namespace HostBrook\LaravelDkim;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Mail\MailServiceProvider;

class DkimMailServiceProvider extends MailServiceProvider
{

    /**
     * Register the Illuminate mailer instance.
     *
     * @return void
     */
    protected function registerIlluminateMailer()
    {
        $this->app->singleton('mail.manager', static function (Application $app) {
            return new MailManager($app);
        });

        $this->app->bind('mailer', static function (Application $app) {
            return $app->make('mail.manager')->mailer();
        });
    }
}