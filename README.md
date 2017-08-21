# systemctl-php
[![Build Status](https://api.travis-ci.org/icanhazstring/systemctl-php.svg?branch=master)](https://travis-ci.org/icanhazstring/systemctl-php) [![Code Climate](https://codeclimate.com/github/icanhazstring/systemctl-php/badges/gpa.svg)](https://codeclimate.com/github/icanhazstring/systemctl-php) [![Test Coverage](https://codeclimate.com/github/icanhazstring/systemctl-php/badges/coverage.svg)](https://codeclimate.com/github/icanhazstring/systemctl-php/coverage)

> WORK IN PROGRESS

PHP wrapper for systemctl (PHP7.1)

# Table of Contents

- [Static Methods](#static-methods)
    - [::setBinary(string $binary)](#setbinarystring-binary)
    - [::setTimeout(int $timeout)](#settimeoutint-timeout)
    - [::setInstallPath(string $installPath)](#setinstallpathstring-installpath)
    - [::setAssetPath(string $assetPath)](#setassetpathstring-assetpath)
  - ["I need sudo to run commands"](#i-need-sudo-to-run-commands)
  - [How do I start/stop/restart a unit?](#how-do-i-startstoprestart-a-unit)
- [Managing units](#managing-units)
  - [Supported units](#supported-units)
  - [Handling unit commands](#handling-unit-commands)
- [Install new units](#install-new-units)
  - [UnitSection](#unitsection)
  - [InstallSection](#installsection)
  - [TypeSpecificSection](#typespecificsection)
- [How to Contribute](#how-to-contribute)

# Static Methods
### ::setBinary(string $binary)
Change the binary executable of `SystemCtl`

```php
SystemCtl::setBinary('/bin/systemctl');
```

### ::setTimeout(int $timeout)
Change the timeout for each command like `start()` on units and `SystemCtl`

```php
SystemCtl::setTimeout(3);
```

### ::setInstallPath(string $installPath)
Change the install path for new units

```php
SystemCtl::setInstallPath('/etc/systemd/system');
```

### ::setAssetPath(string $assetPath)
Change the asset path to look for unit file templates.
The `default` path is relative to the `SystemCtl` vendor package

```php
SystemCtl::setAssetPath('assets');
```

## "I need sudo to run commands"
If you need sudo, you should execute the bin executable with sudo.
The incode support was dropped due to security reason.

## How do I start/stop/restart a unit?
Simply is that. First we instantiate a `SystemCtl` instance an load a unit from a specific type.
Here we use a `Service`. You will always get back `true` if the command succeeded. 
Otherwise the method will throw a `CommandFailedException`.

```php
$systemCtl = new SystemCtl();

// start/stop/enable/disable/reload/restart
$systemCtl->getService('nginx')->start();
$systemCtl->getService('nginx')->stop();
```

# Managing units
To manage any unit u want simply use the proper getter to receive an `Unit` object from `SystemCtl`

## Supported units
- service
- timer

> If you like to see more units feel free to contribute. Other units will be added in the future.

## Handling unit commands
Each unit comes with a range of methods you can invoke on them (like `start()`).
These methods will be dispatched to the `SystemCtl::$binary` you set before hand (or default).

Available unit commands are:
- start
- stop
- enable
- disable
- reload
- restart
- isEnabled
- isActive

# Install new units

> To see a full documentation on how unit files are structure see [Redhat Systemd Documentation](https://access.redhat.com/documentation/en-US/Red_Hat_Enterprise_Linux/7/html/System_Administrators_Guide/sect-Managing_Services_with_systemd-Unit_Files.html)

To install new units you will first need to create a specific `UnitTemplate` (like `ServiceUnitTemplate`).
After you have created a template with a `$name`. You need to define needed values inside the
`Sections` of a unit.

```php
$unitTemplate = new ServiceTemplate('myService');
```

Each unit consists of three sections: The `UnitSection`, `InstallSection` and a `TypeSpecificSection`

## UnitSection
To change the values needed fore the `UnitSection` simple get the section from the template
and set needed values.

**Beware that only set values will be rendered into the template**

> For a full documentation on available methods see [UnitSection](src/Template/Section/UnitSection.php)
```php
$unitTemplate->getUnitSection()->setDescription('My test service');
```

## InstallSection
To change the value needed for the `InstallSection` get the section from the template and set
the needed values.

> For a full documentation on available methods see [InstallSection](src/Template/Section/InstallSection.php)

```php
$unitTemplate->getInstallSection()->setWantedBy(['multi-user.target']);
```

## TypeSpecificSection
Each unit will have a type specific section. These sections are named after the `Type` of the Unit (e.g. `Service`).
To change them, you simply to the same thing as for the others. In case of a `Service` you will do
the following:

> For a full documentation on available methods see [Serviceection](src/Template/Section/ServiceSection.php)

```php
$unitTemplate->getServiceSection()->setType(ServiceSection::TYPE_SIMPLE);
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
PHPUnit 6.3.0 by Sebastian Bergmann and contributors.

................................................................. 65 / 89 ( 73%)
........................                                          89 / 89 (100%)

Time: 1.65 seconds, Memory: 8.00MB

OK (89 tests, 169 assertions)

$ composer cs
> vendor/bin/phpcs --standard=PSR2 src/ && vendor/bin/phpcs --standard=PSR2 tests/

$ 
```

