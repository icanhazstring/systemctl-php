# systemctl-php
PHP wrapper for systemctl (PHP7.1)

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

> If you like to add support for more commands, feel free to contribute.

## How to change the binary

```php
SystemCtl::setBinary('/bin/systemctl');
```

## "I need sudo to run commands"
Don't worry. Simply set sudo flag to `true`.

```php
SystemCtl::sudo(true);
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

