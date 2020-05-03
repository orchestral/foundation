# Changelog for 5.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/foundation`.

## 5.1.0

Released: 2020-05-03

### Changes

* Use Laravel markdown email template for basic notification.

## 5.0.2

Released: 2020-04-22

### Fixes

* Fixes configurating email using `orchestra:configure-email` artisan command.

## 5.0.1

Released: 2020-04-05

### Fixes

* Fixes fallback to configuration for mail setting.

## 5.0.0

Released: 2020-04-03

### Added

* Added `Orchestra\Foundation\Actions\MailConfigurationUpdater`.
* Added `orchestra:configure-mail` command.

### Changes

* Update support for Laravel Framework v7.
* Update Eloquent Hot-Swap capability with `laravie/dhosa`.
