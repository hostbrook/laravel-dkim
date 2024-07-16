<a href="https://github.com/hostbrook/laravel-dkim"><img alt="GitHub release (latest by date)" src="https://img.shields.io/github/v/release/hostbrook/laravel-dkim"></a>
<a href="https://github.com/hostbrook/laravel-dkim"><img alt="GitHub code size in bytes" src="https://img.shields.io/github/languages/code-size/hostbrook/laravel-dkim"></a>
<a href="https://github.com/hostbrook/laravel-dkim"><img alt="License" src="https://img.shields.io/github/license/hostbrook/laravel-dkim"></a>

<p></p>

# Laravel 9.x, 10.x and 11.x DKIM signature

Sign all outgoing emails in Laravel 9.x, 10.x and 11.x with a DKIM signature.

> **IMPORTANT!** The package version that supports Laravel 10.x starts from 1.2.5

> **IMPORTANT!** The package version that supports Laravel 11.x starts from 1.4

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

1. Add your DKIM private key settings in `/.env` and in `/config/mail.php`. 

   3.1. Add DKIM private key settings in `/.env` file:

   ```
   DKIM_SELECTOR="selector1"
   DKIM_DOMAIN="domain.name"
   DKIM_PASSPHRASE=""
   DKIM_PRIVATE_KEY="/storage/app/dkim/private_key.txt"
   ```

   Or, add the full RSA Private Key to the `.env` file, for example:

   ```
   DKIM_PRIVATE_KEY="-----BEGIN RSA PRIVATE KEY-----
   MIIEowIBAAKCAQEAq1SCAScet736Rr/f36OYUo8cRziq4v2uq6kNs5wzEaaqUAoh
   ...
   ENwDlqtgpM9D7YznrL6W9NH7fdSwmz2Ux0frY6weuBx/VSeJn1fb
   -----END RSA PRIVATE KEY-----"
   ```

   3.2. Then in `/config/mail.php` file:

   ```
    'dkim_private_key' => env('DKIM_PRIVATE_KEY', ''),
    'dkim_domain' => env('DKIM_DOMAIN', ''),
    'dkim_selector' => env('DKIM_SELECTOR', 'default'),
    'dkim_passphrase' => env('DKIM_PASSPHRASE', ''),
   ```

## Notes and recommendations

- No matter where you keep the RSA Private Key, everything between two instances `'-----BEGIN RSA PRIVATE KEY-----'` and `'-----END RSA PRIVATE KEY-----'` must be right up to the start of the line!
- It is not recommended to keep private key directly in the `/config/mail.php` file for security reasons especially if your project is not in the private repository.
- If you would like to keep RSA Private key in a text file, the path to the text file must be relative to the project base path (like in the example above).

## Upgrading

Whenever there is a new release, then from the command line in your **_project root_**:

```shell
composer update
```

## Deleting package

Running the following command will remove the package from vendor folder:

```shell
composer remove hostbrook/laravel-dkim
```

## DKIM info

Read more how to:

- [Generate DKIM Public and Private Keys](https://tools.socketlabs.com/dkim/generator)
- [Check if DKIM record is shown and has a correct format](https://dmarcly.com/tools/dkim-record-checker)
- [Test the Spammyness of your Emails](https://www.mail-tester.com)

## References

- [DUDU54/laravel-dkim](https://github.com/DUDU54/laravel-dkim)
- [simonschaufi/laravel-dkim](https://github.com/simonschaufi/laravel-dkim)
