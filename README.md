<a href="https://github.com/hostbrook/laravel-dkim"><img alt="GitHub release (latest by date)" src="https://img.shields.io/github/v/release/hostbrook/laravel-dkim"></a>
<a href="https://github.com/hostbrook/laravel-dkim"><img alt="GitHub code size in bytes" src="https://img.shields.io/github/languages/code-size/hostbrook/laravel-dkim"></a>
<a href="https://github.com/hostbrook/laravel-dkim"><img alt="License" src="https://img.shields.io/github/license/hostbrook/laravel-dkim"></a>

<p></p>

# Laravel 9.x and 10.x DKIM signature

Sign all outgoing emails in Laravel 9.x and 10.x with a DKIM signature.

> **IMPORTANT!** The package version that supports Laravel 10.x starts from 1.2.5

## Installation and setup

1. Get the latest version of the package via Composer:

```
composer require hostbrook/laravel-dkim
```

2. In `config/app.php` comment the line with the original mail service provider (if exists) and add the line with the new mail service provider:

```
// Illuminate\Mail\MailServiceProvider::class,
HostBrook\LaravelDkim\DkimMailServiceProvider::class,
```

3. Add your DKIM private key settings in `/.env` or in `/config/mail.php`. The priority of DKIM settings is from `/.env` file.

   3.1. The syntax, if you want to add DKIM private key settings in `/.env` file:

   ```
   DKIM_SELECTOR="selector1"
   DKIM_DOMAIN="domain.name"
   DKIM_PASSPHRASE=""
   DKIM_PRIVATE_KEY="/storage/app/dkim/private_key.txt"
   ```

   As an option, you can add full RSA Private Key to the `.env` file, for example:

   ```
   DKIM_PRIVATE_KEY="-----BEGIN RSA PRIVATE KEY-----
   MIIEowIBAAKCAQEAq1SCAScet736Rr/f36OYUo8cRziq4v2uq6kNs5wzEaaqUAoh
   ...
   ENwDlqtgpM9D7YznrL6W9NH7fdSwmz2Ux0frY6weuBx/VSeJn1fb
   -----END RSA PRIVATE KEY-----"
   ```

   3.2. The syntax, if you want to add DKIM private key settings in `/config/mail.php` file:

   ```
   'dkim_selector' => 'selector1',
   'dkim_domain' => 'domain.name',
   'dkim_passphrase' => '', // leave empty if you didn’t protect the private key
   'dkim_private_key' => '/storage/app/dkim/private_key.txt',
   ```

   As an option, you can add full RSA Private Key to the `/config/mail.php` file, for example:

   ```
   'dkim_private_key' => '-----BEGIN RSA PRIVATE KEY-----
   MIIEowIBAAKCAQEAq1SCAScet736Rr/f36OYUo8cRziq4v2uq6kNs5wzEaaqUAoh
   ...
   ENwDlqtgpM9D7YznrL6W9NH7fdSwmz2Ux0frY6weuBx/VSeJn1fb
   -----END RSA PRIVATE KEY-----',
   ```

## Notes and recommendations

- Тo matter where you keep RSA Private Key, in `/.env` file or in `/config/mail.php` file or in a text file, everything between two instances `'-----BEGIN RSA PRIVATE KEY-----'` and `'-----END RSA PRIVATE KEY-----'` must be right up to the start of the line!
- If at least one of the parameters (selector, domain, or private key) is not set, the email will be sent (with a warning in the log file), but without the DKIM signature.
- It is not recommended to keep private key directly in the `/config/mail.php` file for security reasons especially if your project is not in the private repository.
- If you would like to keep RSA Private key in a text file, the path to the text file must be relative to the project base path (like in the example above).

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
