# systemctl-php
[![Build Status](https://api.travis-ci.org/icanhazstring/systemctl-php.svg?branch=master)](https://travis-ci.org/icanhazstring/systemctl-php) [![Code Climate](https://codeclimate.com/github/icanhazstring/systemctl-php/badges/gpa.svg)](https://codeclimate.com/github/icanhazstring/systemctl-php) [![Test Coverage](https://codeclimate.com/github/icanhazstring/systemctl-php/badges/coverage.svg)](https://codeclimate.com/github/icanhazstring/systemctl-php/coverage)

PHP wrapper for systemctl

# Table of Contents

- [How to install](#how-to-install)
- [Static Methods](#static-methods)
  - [::setBinary](#setbinarystring-binary)
  - [::setTimeout](#settimeoutint-timeout)
  - [::setInstallPath](#setinstallpathstring-installpath)
  - [::setAssetPath](#setassetpathstring-assetpath)
- ["I need sudo to run commands"](#i-need-sudo-to-run-commands)
- [Managing units](#managing-units)
  - [Supported units](#supported-units)
  - [Unit commands](#unit-commands)
- [Install new units](#install-new-units)
  - [UnitSection](#unitsection)
  - [InstallSection](#installsection)
  - [TypeSpecificSection](#typespecificsection)
  - [UnitInstaller](#unitinstaller)
- [How to Contribute](#how-to-contribute)
- [Credits](#credits)

# How to install
```php
$ composer require icanhazstring/systemctl-php
```

# Static Methods
### ::setBinary(string $binary)
Change the binary executable of `SystemCtl`

> Default: /bin/systemctl

```php
SystemCtl::setBinary('/bin/systemctl');
```

### ::setTimeout(int $timeout)
Change the timeout for each command like `start()` on units and `SystemCtl`

> Default: 3

```php
SystemCtl::setTimeout(3);
```

### ::setInstallPath(string $installPath)
Change the install path for new units

> Default: /etc/systemd/system

```php
SystemCtl::setInstallPath('/etc/systemd/system');
```

### ::setAssetPath(string $assetPath)
Change the asset path to look for unit file templates.
The `default` path is relative to the `SystemCtl` vendor package

> Default: assets

```php
SystemCtl::setAssetPath('assets');
```

# "I need sudo to run commands"
If you need sudo, you should execute the bin executable with sudo.
The incode support was dropped due to security reason.

# Managing units
To manage any unit u want simply use the proper getter to receive an `Unit` object from `SystemCtl`

## Supported units
- service
- timer

> If you like to see more units feel free to contribute. Other units will be added in the future.

## Unit commands
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

```php
$systemCtl = new SystemCtl();

$systemCtl->getService('nginx')->start();
```

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

> For a full documentation on available methods see [ServiceSection](src/Template/Section/ServiceSection.php)

```php
$unitTemplate->getServiceSection()->setType(ServiceSection::TYPE_SIMPLE);
```

## UnitInstaller
To install an new unit, you need to pass the created `UnitTemplate` into `install()`
of `SystemCtl`. This will use the internal `UnitInstaller` to create a unit file located at
`/etc/systemd/system` by default. The `install()` method will also trigger a `daemon-reload` for
systemd. This is needed to load the newly installed unit. Also you will receive an
instance of the new unit you created, so you can manage the behavior of it (e.g. start())

```php
$systemCtl = new SystemCtl;
$unit = $systeCtl->install($unitTemplate);

$unit->start();
```

# How to Contribute
See [CONTIRBUTING.md](CONTRIBUTING.md).

# Credits
This library is heavily influenced by [@mjanser](https://github.com/mjanser) [php-systemctl](https://github.com/mjanser/php-systemctl).
