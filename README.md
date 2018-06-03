# systemctl-php
[![Build Status](https://api.travis-ci.org/icanhazstring/systemctl-php.svg?branch=master)](https://travis-ci.org/icanhazstring/systemctl-php) [![Code Climate](https://codeclimate.com/github/icanhazstring/systemctl-php/badges/gpa.svg)](https://codeclimate.com/github/icanhazstring/systemctl-php) [![Test Coverage](https://codeclimate.com/github/icanhazstring/systemctl-php/badges/coverage.svg)](https://codeclimate.com/github/icanhazstring/systemctl-php/coverage) [![Join the chat at https://gitter.im/icanhazstring/systemctl-php](https://badges.gitter.im/icanhazstring/systemctl-php.svg)](https://gitter.im/icanhazstring/systemctl-php?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

PHP wrapper for systemctl (PHP7.1)

# How to install
```php
$ composer require icanhazstring/systemctl-php
```

## Current supported units
- service
- timer

> If you like to add support for more units, feel free to contribute.

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
Simply clone the repo and install using `composer`

```bash
$ composer install
```

Make your changes and make sure you run *test* and *codesniffer*.

```bash
$ composer test
> vendor/bin/phpunit tests/
PHPUnit 6.1.4 by Sebastian Bergmann and contributors.

........                                                            8 / 8 (100%)

Time: 130 ms, Memory: 2.00MB

OK (8 tests, 13 assertions)

$ composer cs
> vendor/bin/phpcs --standard=PSR2 src/ && vendor/bin/phpcs --standard=PSR2 tests/

$ 
```

# Credits
This library is heavily influenced by [@mjanser](https://github.com/mjanser) [php-systemctl](https://github.com/mjanser/php-systemctl).
