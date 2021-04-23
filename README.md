# KonfigQL

[![Build Status](https://img.shields.io/travis/com/my-vendor/my-package/master.svg?style=for-the-badge&logo=travis)](https://travis-ci.com/my-vendor/my-package) [![PHP Version](https://img.shields.io/packagist/php-v/my-vendor/my-package.svg?style=for-the-badge)](https://github.com/my-vendor/my-package) [![Stable Version](https://img.shields.io/packagist/v/my-vendor/my-package.svg?style=for-the-badge&label=latest)](https://packagist.org/packages/my-vendor/my-package)

## Usage

This assumes you have the OXID eShop up and running and installed and activated the `oxid-esales/graphql-base` module.

### Install

```bash
$ composer require api-coding-days/konfigql
```

After requiring the module, you need to head over to the OXID eShop admin and
activate the module.

### How to use

TBD

## Testing

### Linting, syntax, static analysis and unit tests

```bash
$ composer test
```

### Integration tests

- install this module into a running OXID eShop
- change the `test_config.yml`
  - add module to the `partial_module_paths`
  - set `activate_all_modules` to `true`

```bash
$ ./vendor/bin/runtests
```

## License

TBD
