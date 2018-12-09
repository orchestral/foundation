# Changelog for v3.6

This changelog references the relevant changes (bug and security fixes) done to `orchestra/foundation`.

### 3.6.2

Released: 2018-12-09

### Fixes

* Rename invalid `orchestra.reauth` middleware alias to `orchestra.sudo`.

### 3.6.1

Released: 2018-08-07

### Changes

* Use `app.name` configuration as default application name instead of Orchestra Platform.

### 3.6.0

Released: 2018-06-07

### Added

* Added `Orchestra\Foundation\Support\Providers\Concerns\RouteProvider`.
* Added `Orchestra\Foundation\Concerns\RedirectUsers`.
* Added `Orchestra\Foundation\Concerns\Timezone`.

### Changes

* Update support for Laravel Framework v5.6.
* Rename controllers' `setupMiddleware()` method to `onCreate()`.
* `Orchestra\Foundation\Support\Providers\ModuleServiceProvider` now only register routes if `Illuminate\Contracts\Http\Kernel` can be resolved.
* Rename `Orchestra\Foundation\Testing\Concerns\WithInstallation::install()` to `runInstallation()`.

### Deprecated

* Deprecated `Orchestra\Foundation\Support\Providers\Traits\RouteProvider`, use `Orchestra\Foundation\Support\Providers\Concerns\RouteProvider` instead.
* Deprecated `Orchestra\Foundation\Traits\RedirectUsers`, use `Orchestra\Foundation\Concerns\RedirectUsers` instead.
* Deprecated `Orchestra\Foundation\Traits\Timezone`, use `Orchestra\Foundation\Concerns\Timezone` instead.

### Removed

* Remove `Orchestra\Foundation\Bootstrap\LoadAuthen`.
* Remove `Orchestra\Foundation\Console\Commands\OptimizeCommand`.
* Remove `Orchestra\Foundation\Listeners\UserAccess`.
