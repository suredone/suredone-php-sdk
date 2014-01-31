suredone-php-sdk
================
[![Build Status](https://travis-ci.org/suredone/suredone-php-sdk.png)](https://travis-ci.org/suredone/suredone-php-sdk)
[![Coverage Status](https://coveralls.io/repos/suredone/suredone-php-sdk/badge.png)](https://coveralls.io/r/suredone/suredone-php-sdk)

# Usage

## Running Shipping integration
With token
```sh
php check_shipping.php --engine=ExampleShipping --token=fdsfdsfs342
```

With credentials
```sh
php check_shipping.php --engine=ExampleShipping --username=FOO --password=BAR
```

# Development

## Requirements
 - phpunit
 - keboola/csv (sync inventory)

You can use comporser:
```sh
php composer.phar install --dev --no-interaction
```

## Running tests:
```sh
phpunit
```
