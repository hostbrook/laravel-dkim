# Laravel DKIM signature
Sign all outgoing emails in Laravel 7.x, 8.x, 9.x with a DKIM signature.

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
'dkim_private_key' => '-----BEGIN RSA PRIVATE KEY-----
...your key goes in here...
-----END RSA PRIVATE KEY-----',
```

> **IMPORTANT!** Note, everything between two instances `'-----BEGIN RSA PRIVATE KEY-----'` and `'-----END RSA PRIVATE KEY-----'` must be right up to the start of the line!

## Upgrading

Whenever there is a new release, then from the command line in your ***project root***:

```shell
composer update
```

## DKIM info

Read more how to:
- [Generate DKIM Public and Private Keys](https://tools.socketlabs.com/dkim/generator)
- [Check if DKIM record is shown and has a correct format](https://dmarcly.com/tools/dkim-record-checker)
- [Test the Spammyness of your Emails](https://www.mail-tester.com)