Foundation Change Log
==============

## Version 2.0

### v2.0.23@dev

* Update Twitter Bootstrap v3.0.1.
* Add `orchestra/optimize`.

### v2.0.22

* Fixed `Javie.Events` usage on triggering switcher event.
* Manage Twitter Bootstrap using Composer.
* Implement [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) coding standard.

### v2.0.21

* Add `orchestra/foundation::layout.extra` layout view, to be used for registration, login and forgot password interface.
* Cast possible integer to string on return `ID` from `User` model.
* Improved pagination support by allowing `$perPage` value to be configurable from the model.
* Use explicit Route method instead of `Route::controller()` to increase route resolving performance.
* Move both presenter and validator instance dependencies inject to Controller construct method.
* Move `Orchestra\Model` to it's own repository, this would allow it to be used with orchestra/auth on project without Orchestra Platform.
* Multiple namespace refactors.

### v2.0.20

* Allow Orchestra Platform route to make use of latest `Orchestra\Extension\RequestGenerator` feature allowing subdomain handling.
* Fixed `Orchestra\Mail` using queues doesn't respect configuration from `Orchestra\Memory`.
* Update certain form missing `Input::old()` and `->withInput()` on failed form transaction.
* Multiple code refactors.
* Update assets:
  - underscore.js v1.5.2
  - jQuery v1.10.2
  - Modernizr v2.6.2
  - jQuery UI v1.10.3
  - Select2 v3.4.3

### v2.0.19

* Re-enable reset password e-mail to be sent using configurable e-mail dispatcher (either direct send or queue).
* Allow customization redirection from logout request.
* Multiple tweaks to CSS.
* Mail configuration are now loaded from the database after installation. This would allow developer to use either `Mail` or `Orchestra\Mail` using the same set of configuration out of the box.
* Allow cancelling SMTP e-mail password after change password is clicked.
* Re-organize `Orchestra\Foundation\Services\Event\AdminMenuHandler`.
* Add missing successful reset password message.
* Refactor presenters to use app container to allow IoC overwrite from application.

### v2.0.18

* Rework on `Orchestra\Foundation\Mail` to handle inconsistency using both `Mail::queue()` and `Mail::send()`, add new `Orchestra\Mail::push()` option to allow sending based on configuration.
* Both `Orchestra\Mail::send()` and `Orchestra\Mail::queue()` work as you would using `Mail` equivalent.
* Refactor `Orchestra\Foundation\Reminders\PasswordBroker` to force send email directly even if queue is enabled. This is a limitation with `Illuminate\Support\SerializeClosure` that does support use () to include Closure.
* Fixed messages when registration email is sent using queue, instead of showing failed to send.
* Improve user searching with new `Orchestra\Support\Str::searchable()` API.

### v2.0.17

* Add `orchestra/translation`.
* Improved asset management especially for Twitter Bootstrap.
* Deprecate and remove `subMenu` usage of Navbar Decorator.
* Fixed request to `jquery.min.map` cause 500 errors.
* Separate large view into partial especially on `extensions` and `resources` route for easier theming.
* Fixed some regression bug.

### v2.0.16

* Update to Twitter Bootstrap v3.0.0.
* Slightly improved Navbar Decorator.

### v2.0.15

* Add padding to `.navbar a-navbar.brand` CSS.
* Update Bootstrap 3.0-RC2.
* `Orchestra\Foundation\Reminders\PasswordBroker` should extends `Illuminate\Auth\Reminders\PasswordBroker`.
* Fixed CSS styling based on Bootstrap 3-RC2 changes.
* Fixed inconsistent form styling on reset password page.

### v2.0.14

* Update Twitter Bootstrap 3.
* Change footer to show "Powered by Orchestra Platform" instead of a copyright.
* Tweak installation message on Auth usage.
* Convert prefered function to use `Orchestra\Auth\Acl\Fluent::attach()` instead of `Orchestra\Auth\Acl\Fluent::fill()`.

### v2.0.13

* Replace deprecated call to `Orchestra\Extension::isActive()` and instead use `Orchestra\Extension::activated()`.
* Replace call to `handles('orchestra/foundation::*')` to `handles('orchestra::*')`.
* Small improvement to migration process during installation.
* Add italian translation.
* Revert alias and provides in `Orchestra\Foundation\Services\TestCase`.
* Add `Orchestra\Foundation\Services\ApplicationTestCase`.
* Add testcase for `Orchestra\Foundation\Routing\CredentialController`.
* Fixed CSS issue on create/update User using Select2.

### v2.0.12

* Update to Twitter Bootstrap 3.0.0-RC1.
* Clean-up CSS, JavaScript as well as HTML to match Bootstrap 3.

### v2.0.11

* Fixed typo to `Orchestra\Extension::isActive()`.
* Add safe mode notification when running from safe mode.
* Docblock improvement.

### v2.0.10

* Update Bootstrap 3.
* Remove requirement to use `Illuminate\Support\Fluent` on `Orchestra\Foundation\Services\Validation\UserAccount`.
* Add client-side JavaScript event on each page load.
* Code improvements.
* Fixed `mkdir(): Permission denied` while uploading extension asset using FTP Publisher.

### v2.0.9

* Rename `Orchestra\Foundation\Site::localtime()` to `Orchestra\Foundation\Site::toLocalTime()`.
* Fixed date is not appended when attaching role to a user, add `withTimestamps()` options to both `Orchestra\Model\User` and `Orchestra\Model\Role`.
* Add `Orchestra\Foundation\Site::fromLocalTime()` to convert time from local to what set in `"app.timezone"` config.
* Add `Orchestra\Foundation\Application::locate()` to return relative path to packages/app.

### v2.0.8

* Optimize use of `orchestra/foundation::layout.widgets.header` view.
* Create table and form view for `Orchestra\Html\Table` and `Orchestra\Html\Form`.
* Update Bootstrap 3 files.

### v2.0.7

* Allow `Orchestra\Foundation\Services\UserMetaRepository` to look for data from eloquent before resolving to default.
* Fixed a bug where no roles is assigned to registered user.
* Improved the base grid system to be more readable.
* Multiple bugfixes to the UX and CSS.

### v2.0.6

* Tweak Resources menu to be not shown when all resources are hidden.
* Fixed unable to use Publisher FTP since `Illuminate\Filesystem\Filesystem::makeDirectory()` throws PHP error when creating directory failed.

### v2.0.5

* Move `Orchestra\Services` to `Orchestra\Foundation\Services`.
* Move `Orchestra\Routing` to `Orchestra\Foundation\Routing`.
* All classes are mapped using PSR-0.
* Improved default theme.
* Fixed CSS styling on FTP publisher page.

### v2.0.4

* Allow guest user to access resources if ACL permit.
* Fixed CSS on User search form.

### v2.0.3

* Add `@placeholder("orchestra.resources: {name}")`.
* Add data-id attributes to edit and delete link on users page.

### v2.0.2

* Fixed unable to use `"orchestra.saving: extension.{name}"` event.
* Implement `"orchestra.validate: extension.{name}"` and `Orchestra\Services\Validation\Extension`.
* Fixed regression bug with new implementation on `Orchestra\Support\Validator`.

### v2.0.1

* Tweak Users search form CSS to match Bootstrap 3.
* Fixed a bug where Extension's handles configuration is not accessible.

### v2.0.0

* Migrate `Orchestra\Foundation` from Orchestra Platform 1.2.
* Convert `Orchestra\Core` to `Orchestra\App`.
* Add `Orchestra\App::handle()` to emulate `(:bundle)` routing structure in Laravel 3.
* Deprecate and remove `Orchestra`, alias to `Orchestra\App`, instead introduce `orchestra()` helper function as a replacement.
* Validations now using services, based on `Orchestra\Support\Validator`.
* Menu are now using `Orchestra\Services\Event\AdminMenuHandler`.
* `Orchestra\Mail` are now utilising Laravel 4 `Mail` class, `Orchestra\Mail::send()` would choose either to use basic send or queue based on Orchestra Platform setting.
* Fixes bad references to `Orchestra\Support\Str` on `Orchestra\Routing\ForgotController` and `Orchestra\Routing\RegisterController`.
* Update to Twitter Bootstrap 3.
* Replace current implementation for reset password with Laravel 4, with some goodies from Orchestra Platform.
* Remove `Form::token()` as it's automatically added by `Form::open()`.
* Add support to use `sendmail` as e-mail transport.
* Reduce usage of Blade syntax, since `{` and `}` can be customised by the user Orchestra Platform shouldn't depend on it.
* Fixed invalid generated URL to delete users.
* Add `Orchestra\Testbench` package to help unit testing controllers.
* Allow `handles('orchestra::/')` to alias `handles('orchestra/foundation::/')`.
* Add `resources('foo')` to alias `handles('orchestra/foundation::resources/foo')`.
