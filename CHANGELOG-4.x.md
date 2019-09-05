# Changelog for 4.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/foundation`.

## 4.0.0

### Changes

* Update support for Laravel Framework v6.0+.
* Change removed `Input` facade usage with `Illuminate\Http\Request` DI or `Request` facade whenever possible.
* Replace `Mpociot\Reauthenticate` with `Orchestra\Reauthenticate`.

### Deprecated

* Deprecate `Orchestra\Foundation\Providers\NovaServiceProvider`, use `Orchestra\Foundation\Providers\HttpServiceProvider` instead.

### Removed

* Remove `Orchestra\Foundation\Providers\MiddlewareServiceProvider` service provider, use or extends `Orchestra\Foundation\Providers\HttpServiceProvider` instead.
* Remove `Orchestra\Foundation\Bootstrap\LoadUserMetaData` bootstrapper.
* Remove `assetic()` helper method.
* Remove support for `mandrill` and `sparkpost` email options.
