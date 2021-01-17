# Laravel package to manage and create barcodes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/Max13/laravel-barcode.svg?style=flat-square)](https://packagist.org/packages/Max13/laravel-barcode)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/Max13/laravel-barcode/run-tests?label=tests)](https://github.com/Max13/laravel-barcode/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/Max13/laravel-barcode.svg?style=flat-square)](https://packagist.org/packages/Max13/laravel-barcode)

With this package you can manage barcodes (external barcodes, checkings, etc…) or generate your own as long as you configure it correctly.

## Installation

You can install the package via composer:

```bash
composer require max13/laravel-barcode
```

This package embeds corresponding fonts to write the barcode on a webpage. You will see how to use them in the [Usage](#Usage) section below.

## Configuration

`Barcode` types are available like so (defaults to `ean13`):

| type | has font |
| --- | --- |
| ean13 | yes |

To change the barcode type used, set to your `.env` file: `BARCODE_TYPE=` with the barcode type you want to use.

When a barcode has a font and you would use it, you need to publish the package assets using:

```bash
php artisan vendor:publish --provider="Max13\Barcode\ServiceProvider" --tag=public
```

Don’t forget to include the css files in your layouts.

If you need to see or change this package config file, you can publish the config file using this command:

```bash
php artisan vendor:publish --provider="Max13\Barcode\ServiceProvider" --tag=config
```

This will export the config file to your app’s config folder.

## Usage

```php
// To check an ean13 barcode:
if (Barcode::isValid('0123456789104')) {
    // This barcode is valid
}

// To calculate an ean13 barcode check digit (the last digit)
echo Barcode::checksum('012345678910'); // Show: '4'

// To fix an ean13 barcode if it's needed
echo Barcode::fix('0123456789100'); // Show: '0123456789104'
```

And finally, if you need to write the barcode using the embedded font, you need to get the encoded form of the barcode (ex: in case of `ean13`, the font will be applied on html class `f-ean13`):

```html
<span class="f-ean13">{{ Barcode::encode('0123456789104') }}</span>
```

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Credits

- [Adnan RIHAN](https://github.com/Max13)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
