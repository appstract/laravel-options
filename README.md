# Laravel Options

[![Latest Version on Packagist](https://img.shields.io/packagist/v/appstract/laravel-options.svg?style=flat-square)](https://packagist.org/packages/appstract/laravel-options)
[![Total Downloads](https://img.shields.io/packagist/dt/appstract/laravel-options.svg?style=flat-square)](https://packagist.org/packages/appstract/laravel-options)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/appstract/laravel-options.svg?branch=master)](https://travis-ci.org/appstract/laravel-options)

__For Laravel ^5.4|^6.x, check [version 3.0](https://github.com/appstract/laravel-options/tree/3.0.0)__
```shell script
# Laravel ^5.4|^6.x installation:
composer require appstract/laravel-options ^3.0
```

Global key-value store in the database

## Installation

To get started with laravel-options, use Composer to add the package to your project's dependencies:

```bash
composer require appstract/laravel-options
```

### Publish, migrate

By running `php artisan vendor:publish --provider="Appstract\Options\OptionsServiceProvider"` in your project all files for this package will be published.
For this package, it's only has a migration.
Run `php artisan migrate` to migrate the table.
There will now be an `options` table in your database.

## Usage

With the `option()` helper, we can get and set options:

```php
// Get option
option('someKey');

// Get option, with a default fallback value if the key doesn't exist
option('someKey', 'Some default value if the key is not found');

// Set option
option(['someKey' => 'someValue']);

// Check the option exists
option_exists('someKey');
```

If you want to check if an option exists, you can use the facade:

```php
use Option;

$check = Option::exists('someKey');
```

Setting a value to a key that already exists will [overwrite the value](https://github.com/appstract/laravel-options/releases/tag/0.2.0).


### Console

It is also possible to set options within the console:

```bash
php artisan option:set {someKey} {someValue}
```

## Testing

```bash
$ composer test
```

## Contributing

Contributions are welcome, [thanks to y'all](https://github.com/appstract/laravel-options/graphs/contributors) :)

## About Appstract

Appstract is a small team from The Netherlands. We create (open source) tools for webdevelopment and write about related subjects on [Medium](https://medium.com/appstract). You can [follow us on Twitter](https://twitter.com/teamappstract), [buy us a beer](https://www.paypal.me/teamappstract/10) or [support us on Patreon](https://www.patreon.com/appstract).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
