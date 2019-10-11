# Laravel Options

[![Latest Version on Packagist](https://img.shields.io/packagist/v/appstract/laravel-options.svg?style=flat-square)](https://packagist.org/packages/appstract/laravel-options)
[![Total Downloads](https://img.shields.io/packagist/dt/appstract/laravel-options.svg?style=flat-square)](https://packagist.org/packages/appstract/laravel-options)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/appstract/laravel-options/master.svg?style=flat-square)](https://travis-ci.org/appstract/laravel-options)

Global key-value store in the database

## Installation

To get started with laravel-options, use Composer to add the package to your project's dependencies:

```bash
composer require appstract/laravel-options
```

### Publish, migrate

By running `php artisan vendor:publish --provider="Appstract\Options\OptionsServiceProvider"` in your project all files for this package will be published.
For this package, it's only a migration. Run `php artisan migrate` to migrate the table.
There will now be an `options` table in your database.

## Usage

With the `option()` helper, we can get and set options:

```php
// Get option
option('someKey');

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
