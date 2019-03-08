# Changelog for v3.7

This changelog references the relevant changes (bug and security fixes) done to `orchestra/foundation`.

### 3.7.2

Released: 2019-03-08

### Changes

* Allows developers to remove Orchestra Platform admin routes if they want to use it with other administation panels.

### 3.7.1

Released: 2019-03-03

### Changes

* Improve performance by prefixing all global functions calls with `\` to skip the look up and resolve process and go straight to the global function.

### 3.7.0

Released: 2019-01-14

### Added

* Add `Orchestra\Foundation\Publisher\Filesystem` as replacement to FTP publisher.
* Overwrite `Orchestra\Foundation\Application::registerConfiguredProviders()` to consider `Orchestra` namespace to be loaded first before discovered packages.

### Changes

* Update support for Laravel Framework v5.7.
* Rename `Orchestra\Foundation\Processor` namespace to `Orchestra\Foundation\Processors`.
* Rename `Orchestra\Foundation\Validation` namespace to `Orchestra\Foundation\Validations`.
* Restucture routing.
