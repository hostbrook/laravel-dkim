<?php

/**
 * HostBrook note:
 * The source code of Srvice Provider is taken from here:
 * https://github.com/DUDU54/laravel-dkim
 */

namespace HostBrook\LaravelDkim;

use Illuminate\Mail\MailServiceProvider;

class DkimMailServiceProvider extends MailServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerIlluminateMailer();
        parent::registerMarkdownRenderer();
    }

    /**
     * Register the Illuminate mailer instance.
     *
     * @return void
     */
    protected function registerIlluminateMailer()
    {
        $this->app->singleton('mail.manager', function ($app) {
            return new MailManager($app);
        });

        $this->app->bind('mailer', function ($app) {
            return $app->make('mail.manager')->mailer();
        });
    }
}