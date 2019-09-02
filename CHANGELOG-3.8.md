# Changelog for v3.8

This changelog references the relevant changes (bug and security fixes) done to `orchestra/foundation`.

## 3.8.3

Released: 2019-09-02

### Added

* Added `Orchestra\Foundation\Providers\HttpServiceProvider`.

### Changes

* Use `Orchestra\Model\Eloquent::usesTransaction()`.

### Deprecated

* Deprecate `Orchestra\Foundation\Providers\MiddlewareServiceProvider`.

## 3.8.2

Released: 2019-08-09

### Changes

* Improve performance by prefixing all global functions calls with `\` to skip the look up and resolve process and go straight to the global function.
* Use `static function` rather than `function` whenever possible, the PHP engine does not need to instantiate and later GC a `$this` variable for said closure.

### Fixes

* Fixed flash messages not being stored to session on redirection.

## 3.8.1

Released: 2019-04-15

### Changes

* Improves notification emails for reset password and welcome user.

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
