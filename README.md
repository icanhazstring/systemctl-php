# systemctl-php
![CI Pipeline](https://github.com/icanhazstring/systemctl-php/workflows/CI%20Pipeline/badge.svg) [![Code Climate](https://codeclimate.com/github/icanhazstring/systemctl-php/badges/gpa.svg)](https://codeclimate.com/github/icanhazstring/systemctl-php) [![Test Coverage](https://codeclimate.com/github/icanhazstring/systemctl-php/badges/coverage.svg)](https://codeclimate.com/github/icanhazstring/systemctl-php/coverage) [![Join the chat at https://gitter.im/icanhazstring/systemctl-php](https://badges.gitter.im/icanhazstring/systemctl-php.svg)](https://gitter.im/icanhazstring/systemctl-php?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

PHP wrapper for systemctl

# How to install
```php
$ composer require icanhazstring/systemctl-php
```

## Current supported units
See [Units](src/Unit)

> If you like to add support for more units, feel free to grab an issue and contribute.

## Current supported commands
- start
- stop
- enable
- disable
- reload
- restart
- isEnabled
- isActive

> If you like to add support for more commands, feel free to contribute.

## How to change the binary

```php
SystemCtl::setBinary('/bin/systemctl');
```

## How to change command timeout
To change command tmeout simply call the static method `setTimeout`.
```php
SystemCtl::setTimeout(10);
```

> The default timeout is set to `3` seconds

## "I need sudo to run commands"
If you need sudo, you should execute the bin executable with sudo.
The incode support was dropped due to security reason.

## How do I start/stop/restart a unit?
Simply is that. First we instantiate a `SystemCtl` instance an load a unit from a specific type. Here we use a `Service`. You will always get back `true` if the command succeeded. Otherwise the method will throw a `CommandFailedException`.

```php
$systemCtl = new SystemCtl();

// start/stop/enable/disable/reload/restart
$systemCtl->getService('nginx')->start();
$systemCtl->getService('nginx')->stop();
```

# How to Contribute
Clone the repo and install using `composer`

```bash
$ composer install
```

Make your changes and make sure you run *test* and *codesniffer*.

```bash
$ composer test
> vendor/bin/phpunit
PHPUnit 9.5.3 by Sebastian Bergmann and contributors.

...............................................................  63 / 128 ( 49%)
............................................................... 126 / 128 ( 98%)
..                                                              128 / 128 (100%)

Time: 00:00.033, Memory: 10.00 MB

OK (128 tests, 192 assertions)

$ composer cs
> vendor/bin/phpcs --standard=PSR2 src/ && vendor/bin/phpcs --standard=PSR2 tests/

$
```

# Credits
This library is heavily influenced by [@mjanser](https://github.com/mjanser) [php-systemctl](https://github.com/mjanser/php-systemctl).
