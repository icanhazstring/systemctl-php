# Changelog

## [Unreleased]
### Added
- Added `Target` unit ([#38](https://github.com/icanhazstring/systemctl-php/pull/38)) (thanks to [@peter279k](https://github.com/peter279k))
- Added `Swap` unit ([#39](https://github.com/icanhazstring/systemctl-php/pull/39)) (thanks to [@peter279k](https://github.com/peter279k))
- Added `Automount` unit ([#40](https://github.com/icanhazstring/systemctl-php/pull/40)) (thanks to [@peter279k](https://github.com/peter279k))
- Added `Mount` unit ([#41](https://github.com/icanhazstring/systemctl-php/pull/41)) (thanks to [@peter279k](https://github.com/peter279k))
- Migrate `phpunit.xml` for new version ([#42](https://github.com/icanhazstring/systemctl-php/pull/42)) (thanks to [@peter279k](https://github.com/peter279k))
- Replace Travis CI with GitHub Action status badge ([#44](https://github.com/icanhazstring/systemctl-php/pull/44)) (thanks to [@peter279k](https://github.com/peter279k))

## [0.8.0] - 2020-11-05
### Added
- Added `Slice` unit ([#36](https://github.com/icanhazstring/systemctl-php/pull/36)) (thanks to [@peter279k](https://github.com/peter279k))
- Added method `SystemCtl::reset-failed()` ([#37](https://github.com/icanhazstring/systemctl-php/pull/37)) (thanks to [@icanhazstring](https://github.com/icanhazstring))

### Changed
- Dropped support for php7.2
- Dropped support for `symfony/process:^4.4`

## [0.7.1] - 2020-05-27
### Added
- Added `Scope` unit ([#32](https://github.com/icanhazstring/systemctl-php/pull/32)) (thanks to [@peter279k](https://github.com/peter279k))

## [0.7.0] - 2020-02-16
### Changed
- Moved classes to different namespace (`SystemCtl` to `icanhazstring\Systemctl`)
- Dropped support for PHP7.1
- Dropped support for `symfony/process:^3.x`

### Added
- Added `CHANGELOG.md` and `MIGRATION.md`
- Added support for `symfony/process:^4.4 || ^5.0`
- Added code quality tools

## Previous releases
- No changelog available
