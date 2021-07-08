# Laravel DKIM signature
Sign all outgoing emails in Laravel ^7.4, ^8.0 with a DKIM signature.

## Installation and setup

1. Install via Composer:
```
composer require hostbrook/laravel-dkim
```

2. In `config/app.php` comment line with original mail service provider and add the line with new mail servoce provider's:
```
// Illuminate\Mail\MailServiceProvider::class,
Hostbrook\LaravelDkim\DkimMailServiceProvider::class,
```

4. Add your private key settings in `config/mail.php`:
```
'dkim_selector' => YOUR_SELECTOR,
'dkim_domain' => DOMAIN_NAME,
'dkim_private_key' => '-----BEGIN RSA PRIVATE KEY-----
...your key goes in here...
-----END RSA PRIVATE KEY-----',

```

NOTE: everything between the two instances '-----BEGIN RSA PRIVATE KEY-----' and '-----END RSA PRIVATE KEY-----' must be right up to the start of the line.

## DKIM info

Read more how to:
- [Generate DKIM Public and Private Keys](https://tools.socketlabs.com/dkim/generator)
- [Check if DKIM record is shown and has a correct format](https://dmarcly.com/tools/dkim-record-checker)
- [Test the Spammyness of your Emails](https://www.mail-tester.com)