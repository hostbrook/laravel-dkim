<a href="https://github.com/hostbrook/laravel-dkim"><img alt="GitHub release (latest by date)" src="https://img.shields.io/github/v/release/hostbrook/laravel-dkim"></a>
<a href="https://github.com/hostbrook/laravel-dkim"><img alt="GitHub code size in bytes" src="https://img.shields.io/github/languages/code-size/hostbrook/laravel-dkim"></a>
<a href="https://github.com/hostbrook/laravel-dkim"><img alt="License" src="https://img.shields.io/github/license/hostbrook/laravel-dkim"></a>

</p>

# Laravel DKIM signature

Sign all outgoing emails in Laravel 9.x with a DKIM signature.

> **IMPORTANT!** Be aware! Laravel 9.x uses Symfony Mailer as a mail service provider. There is a known bug in Symfony Mailer DkimSigner: if mail is built with multi parts (HTML and PLAIN TEXT) the result DKIM signature is invalid. So, if you need to sign with DKIM in Laravel 9.x, either you have to avoid building multi-part emails or continue using disappeared Swift Mailer. [More about the Symfony bug](https://github.com/symfony/symfony/issues/39354)

## Installation and setup

1. Get the latest version of the package via Composer:

```
composer require hostbrook/laravel-dkim
```

2. In `config/app.php` comment the line with the original mail service provider and add the line with the new mail service provider:

```
// Illuminate\Mail\MailServiceProvider::class,
HostBrook\LaravelDkim\DkimMailServiceProvider::class,
```

3. Add your private key settings in `config/mail.php`:

```
'dkim_selector' => YOUR_SELECTOR, // for example: 'Selector123'
'dkim_domain' => DOMAIN_NAME,     // for example: 'myblog.com'
'dkim_passphrase' => '', // leave empty if you didn’t protect the private key
'dkim_private_key' => '-----BEGIN RSA PRIVATE KEY-----
...your key goes in here...
-----END RSA PRIVATE KEY-----',
```

> **IMPORTANT!** Note, everything between two instances `'-----BEGIN RSA PRIVATE KEY-----'` and `'-----END RSA PRIVATE KEY-----'` must be right up to the start of the line!

## Upgrading

Whenever there is a new release, then from the command line in your **_project root_**:

```shell
composer update
```

## DKIM info

Read more how to:

- [Generate DKIM Public and Private Keys](https://tools.socketlabs.com/dkim/generator)
- [Check if DKIM record is shown and has a correct format](https://dmarcly.com/tools/dkim-record-checker)
- [Test the Spammyness of your Emails](https://www.mail-tester.com)

## References

- [DUDU54/laravel-dkim](https://github.com/DUDU54/laravel-dkim)
- [simonschaufi/laravel-dkim](https://github.com/simonschaufi/laravel-dkim)
