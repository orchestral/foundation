# Changelog for 4.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/foundation`.

## 4.2.0

Released: 2020-01-04

### Added

* Added `Orchestra\Foundation\Jobs\SyncDefaultAuthorization`.
* Added `Orchestra\Foundation\Jobs\UpdateMailConfiguration`.

### Changes

* Improves eloquent hot-swap implementation. 
* Generated installer will defaults to generated admin email from factory instead of hardcoded email address. 
* Set command exit code.
* Update `Orchestra\Foundation\Auth\User::routeNotificationFor()`.

### Fixes

* Fixes `use_timezone()` usage.

## 4.1.0

Released: 2019-10-11

### Changes

* Update support for Laravel Framework v6.2+.

## 4.0.1

Released: 2019-10-10

### Fixes

* Replace `str_limit()` with `Illuminate\Support\Str::limit()`.

## 4.0.0

Released: 2019-09-14

### Changes

* Update support for Laravel Framework v6.
* Change removed `Input` facade usage with `Illuminate\Http\Request` DI or `Request` facade whenever possible.
* Replace `Mpociot\Reauthenticate` with `Orchestra\Reauthenticate`.
* Resolve `User` and `Role` models using `Orchestra\Model\HS`.

### Deprecated

* Deprecate `Orchestra\Foundation\Providers\NovaServiceProvider`, use `Orchestra\Foundation\Providers\HttpServiceProvider` instead.

### Removed

* Remove `Orchestra\Foundation\Providers\MiddlewareServiceProvider` service provider, use or extends `Orchestra\Foundation\Providers\HttpServiceProvider` instead.
* Remove `Orchestra\Foundation\Bootstrap\LoadUserMetaData` bootstrapper.
* Remove `assetic()` helper method.
* Remove support for `mandrill` and `sparkpost` email options.
