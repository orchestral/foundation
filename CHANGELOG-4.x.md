# Changelog for 4.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/foundation`.

## 4.0.0

### Deprecated

* Deprecate `Orchestra\Foundation\Providers\NovaServiceProvider`, use `Orchestra\Foundation\Providers\HttpServiceProvider` instead.

### Removed

* Remove `Orchestra\Foundation\Providers\MiddlewareServiceProvider` service provider, use or extends `Orchestra\Foundation\Providers\HttpServiceProvider` instead.
* Remove `assetic()` helper method.
