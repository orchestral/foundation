# Changelog for v3.5

This changelog references the relevant changes (bug and security fixes) done to `orchestra/foundation`.

## 3.5.2

Released: 2018-03-19

### Changes

* Wrap `Orchestra\Foundation\Testing\Installation` to use `afterApplicationCreated()`.

### Fixes

* Fallback to use `/admin` if configuration can't be resolved.
* Update `Command::fire()` to `Command::handle()`.

## 3.5.1

Released: 2018-02-21

### Changes

* Update `Orchestra\Foundation\RouteResolver` to use `orchestra.extension.url`.
* Tweak how installation is detected during testing.

## 3.5.0

Released: 2017-12-26

### Changes

* Update support for Laravel Framework v5.5.
