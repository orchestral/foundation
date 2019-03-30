# Changelog for v3.8

This changelog references the relevant changes (bug and security fixes) done to `orchestra/foundation`.

## 3.8.0

Released: 2019-03-30

### Added

* Added `Orchestra\Foundation\Observers\UserObserver`.
* Added `Orchestra\Foundation\Tools\GenerateRandomPassword`.
* Added `carbonize()` and `use_timezone()` helpers.

### Changes

* Update support for Laravel Framework v5.8.
* Update `nesbot/carbon` minimum version to `^2.0`.

### Removed

* Remove deprecated classes:
    - `Orchestra\Foundation\Processor\Processor`.
    - `Orchestra\Foundation\Support\Traits\RouteProvider`.
    - `Orchestra\Foundation\Traits\RedirectUser`.
* Remove `Orchestra\Foundation\Traits\Timezone`.
