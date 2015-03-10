---
title: Foundation Change Log

---

## Version 3.0 {#v3-0}

## v3.0.5 {#v3-0-5}

* Fixes unable to resolve `Illuminate\Contracts\Auth\Authenticatable` through IoC Container when using `php artisan route:list`.

## v3.0.4 {#v3-0-4}

* Rework `Orchestra\Foundation\Filters\VerifyCsrfToken` to accept `X-CSRF-TOKEN` (raw token) as well as `X-XSRF-TOKEN` (encrypted token).
* Push foundation menu handlers to `orchestra.started: admin` instead of `orchestra.ready: admin` to support Laravel Framework changes to middleware handling in v5.0.6.

## v3.0.3 {#v3-0-3}

* Fixes `Orchestra\Foundation\Support\Providers\RouteServiceProvider` to be able to run `setRootControllerNamespace()` and `loadCachedRoutes()` during booting process.
* Add `Orchestra\Foundation\Support\Providers\ExtensionRouteServiceProvider` for extensions or modules routing.

## v3.0.2 {#v3-0-2}

* Refactor `Orchestra\Foundation\Support\Providers\RouteServiceProvider::loadFrontendRoutesFrom()` to utilize `Orchestra\Foundation\Foundation::group()` instead of `Illuminate\Routing\Router::group()`.
* Add `Orchestra\Foundation\Support\Providers\RouteServiceProvider::afterExtensionLoaded()` helper method.

### v3.0.1 {#v3-0-1}

* Add `Orchestra\Foundation\Support\Providers\RouteServiceProvider`.
* Refactor `Orchestra\Foundation\Support\MenuHandler` to be more usable.
* Refactor `Orchestra\Foundation\AdminMenuHandler` and split the handling to following classes:
  - `Orchestra\Foundation\Http\Handlers\ExtensionMenuHandler`.
  - `Orchestra\Foundation\Http\Handlers\ResourcesMenuHandler`.
  - `Orchestra\Foundation\Http\Handlers\SettingMenuHandler`.
  - `Orchestra\Foundation\Http\Handlers\UserMenuHandler`.

### v3.0.0 {#v3-0-0}

* Update support to Laravel Framework v5.0.
* Simplify PSR-4 path.
* Rename `Orchestra\Foundation\Application` to `Orchestra\Foundation\Foundation`.
* Add `Orchestra\Foundation\Application`, extending `Illuminate\Foundation\Application`.
* Add `Orchestra\Foundation\Providers\ArtisanServiceProvider`.
* Add `Orchestra\Foundation\Providers\FilterServiceProvider`.
* Rename `Orchestra\Foundation\ConsoleSupportServiceProvider` to `Orchestra\Foundation\Providers\ConsoleSupportServiceProvider`.
* Rename `Orchestra\Foundation\FoundationServiceProvider` to `Orchestra\Foundation\Providers\FoundationServiceProvider`.
* Rename `Orchestra\Foundation\SiteServiceProvider` to `Orchestra\Foundation\Providers\SupportServiceProvider`.
* Convert all closure based filters to classes under `Orchestra\Foundation\Filters` namespace.
* Convert all start-up files to classes under `Orchestra\Foundation\Bootstrap` namespace.
* Allow `orchestra/foundation` routing to be cached.
* Add `Orchestra\Foundation\Traits\AliasesProviderTrait`.
* Move password reset code to `orchestra/auth`.
* Add `orchestra/kernel` as a dependencies.
* Convert all `Orchestra\Foundation\Foundation` services to IoC bindings.
* Add `get_meta()` and `set_meta()` helper function.
* Add `predis/predis` (~1.0) as a dependency.
* Assets:
  - Update Twitter Bootstrap v3.3.2.
  - Update Javie v1.2.0.

## Version 2.2 {#v2-2}

### v2.2.9 {#v2-2-9}

* Fixes missing csrf token missmatch checking on delete user request.

### v2.2.8 {#v2-2-8}

* Convert `orchestra.csrf` filter closure to `Orchestra\Foundation\Filters\VerifyCsrfToken`.
* Attach csrf to addition routes.

### v2.2.7 {#v2-2-7}

* Use timing safe string comparison in CSRF filter.

### v2.2.6 {#v2-2-6}

* Check for session token type.
* Add `orchestra/publisher` as a dependency.

### v2.2.5 {#v2-2-5}

* Update all reference to utilise `orchestra/messages`.
* Add `Orchestra\Foundation\Installation\InstallerInterface::bootInstallerFiles()` for future compatibility.
* Utilize method chaining when building form and table.
* Improve `bower` usage by including `bower-installer`.

### v2.2.4 {#v2-2-4}

* Deprecate `orchestra.validate: user.registration` event and replace it with `orchestra.validate: user.account.register` event.
* Include `orchestra/messages` as requirement and add breaking change to type-hint `Orchestra\Messages\MessageBag` instead of `Orchestra\Support\Messages`.
* Utilize `Illuminate\Support\Arr`.
* Update Javie v1.1.6.

### v2.2.3 {#v2-2-3}

* Fixed event names on registration.
* Add new `orchestra.validate: user.registration` event.

### v2.2.2 {#v2-2-2}

* Utilize `orchestra/notifier` new `Orchestra\Notifier\Message::create()` helper method.
* Allow `Orchestra\Foundation\Application::group()` to mimic `Route::group()` functionality.
* Allow default routes to be configurable.

### v2.2.1 {#v2-2-1}

* Fixed error with inline help `<span>`, helper `<span>` and errors for `select[role="switcher"]` HTML.
* Utilize `orchestra/notifier` new `Orchestra\Notifier\Message` class.
* Update to Twitter Bootstrap v3.2.0.

### v2.2.0 {#v2-2-0}

* Bump minimum version to PHP v5.4.0.
* Rename Environment to Factory.
* Only implement abstract method `Orchestra\Foundation\Routing\BaseController::setupFilters()` on implementations.
* Allow Extension to be optional.
* Increased the CSS width of `th.actions` for properly handling label in different language.
* Update to Twitter Bootstrap v3.1.1.
* Open hyperlink to extension author's URL on a new window/tab.
* Allow all type-hinting to resolve to available service locator in Orchestra Platform.
* Improve DI on `Orchestra\Foundation\AdminMenuHandler`.
* Eagerly attach `orchestra/memory` during installation.
* Provides actual service locator for `Orchestra\Foundation\ConsoleSupportServiceProvider`.
* Manage some asset packages using bower.
* Change code to support new `orchestra/publisher` component.
* Add support for `orchestra/view` command.

## Version 2.1 {#v2-1}

### v2.1.15 {#v2-1-15}

* Fixes missing csrf token missmatch checking on delete user request.

### v2.1.14 {#v2-1-14}

* Convert `orchestra.csrf` filter closure to `Orchestra\Foundation\Filters\VerifyCsrfToken`.
* Attach csrf to addition routes.

### v2.1.13 {#v2-1-13}

* Use timing safe string comparison in CSRF filter.

### v2.1.12 {#v2-1-12}

* Check for session token type.
* Add `orchestra/publisher` as a dependency.

### v2.1.11 {#v2-1-11}

* Deprecate `orchestra.validate: user.registration` event and replace it with `orchestra.validate: user.account.register` event.
* Update Javie v1.1.6.

### v2.1.10 {#v2-1-10}

* Fixed event names on registration.
* Add new `orchestra.validate: user.registration` event.

### v2.1.9 {#v2-1-9}

* Allow `Orchestra\Foundation\Application::group()` to mimic `Route::group()` functionality.
* Allow default routes to be configurable.

### v2.1.8 {#v2-1-8}

* Fixed error with inline help `<span>`, helper `<span>` and errors for `select[role="switcher"]` HTML.
* Update to Twitter Bootstrap v3.2.0.

### v2.1.7 {#v2-1-7}

* Manage some asset packages using bower.
* Change code to support new `orchestra/publisher` component.
* Add support for `orchestra/view` command.

### v2.1.6 {#v2-1-6}

* Fixes test case involving exception return when URL matched a different verb.
* Add breaking backward compatibility by changing the parameter on `Orchestra\Foundation\Processor\User::index()` to allow better customizations.

### v2.1.5 {#v2-1-5}

* Implement [PSR-4](https://github.com/php-fig/fig-standards/blob/master/proposed/psr-4-autoloader/psr-4-autoloader.md) autoloading structure.

### v2.1.4 {#v2-1-4}

* Allow Extension to be optional.
* Increased the CSS width of `th.actions` for properly handling label in different language.
* Update to Twitter Bootstrap v3.1.1.
* Add backward compatibility to `v2.0` password reset routing (allow `v2.0` based theme to work on `v2.1`).
* Minor tweaks to routing to improve consistency.

### v2.1.3 {#v2-1-3}

* Show database connection issues (if any) during installation process.
* Simplify roles detection using `Orchestra\Model\User::getRoles()`.
* Update to Twitter Bootstrap v3.1.0.

### v2.1.2 {#v2-1-2}

* Open hyperlink to extension author's URL on a new window/tab.
* Allow all type-hinting to resolve to available service locator in Orchestra Platform.
* Improve DI on `Orchestra\Foundation\AdminMenuHandler`.
* Eagerly attach `orchestra/memory` during installation.
* Multiple refactor.
* Handle `orchestra/memory` on `orchestra.mail` service locator from `orchestra/foundation`.

### v2.1.1 {#v2-1-1}

* Fixes missing select2 on search user filter.
* Provides actual service locator for `Orchestra\Foundation\ConsoleSupportServiceProvider`.

### v2.1.0 {#v2-1-0}

* Split `Orchestra\Foundation\Services\AdminMenuHandler@handle` to allow easier customization.
* Implement `"orchestra.validate: extension.{name}"` and `Orchestra\Services\Validation\Extension`.
* Add `@placeholder("orchestra.resources: {name}")`.
* Add data-id attributes to edit and delete link on users page.
* Allow guest user to access resources if ACL permit.
* Allow `Orchestra\Foundation\Services\UserMetaRepository` to look for data from eloquent before resolving to default.
* Rename `Orchestra\Foundation\Site::localtime()` to `Orchestra\Foundation\Site::toLocalTime()`.
* Add `Orchestra\Foundation\Site::fromLocalTime()` to convert time from local to what set in `"app.timezone"` config.
* Add `Orchestra\Foundation\Application::locate()` to return relative path to packages/app.
* Add client-side JavaScript event on each page load.
* Add safe mode notification when running from safe mode.
* Replace deprecated call to `Orchestra\Extension::isActive()` and instead use `Orchestra\Extension::activated()`.
Replace call to `handles('orchestra/foundation::*')` to `handles('orchestra::*')`.
* Add italian translation.
* Convert prefered function to use `Orchestra\Auth\Acl\Fluent::attach()` instead of `Orchestra\Auth\Acl\Fluent::fill()`.
* Improve user searching with new `Orchestra\Support\Str::searchable()` API.
* Refactor presenters to use app container to allow IoC overwrite from application.
* Allow cancelling SMTP e-mail password after change password is clicked.
* Allow Orchestra Platform route to make use of latest `Orchestra\Extension\RouteGenerator` feature allowing subdomain handling.
* Predefined package path to avoid additional overhead to guest package path.
* Only display resources in navigation menus if not hidden.
* Consolidate all commands service provider in `Orchestra\Foundation\ConsoleSupportServiceProvider`.
* Refactor `Orchestra\Foundation\Reminders\PasswordBroker` and `Orchestra\Foundation\Routing\ForgotController` based on Laravel 4.1 changes.
* Refactor routing as passive controllers and move all CRUD action to `Orchestra\Foundation\Processor` namespace.
* Move `Orchestra\Foundation\UserMetaRepository` and `Orchestra\Foundation\UserMetaProvider` to orchestra/model.
* Add components:
  - `orchestra/debug` for profiling your Orchestra Platform application.
  - `orchestra/optimize` to run autoloading optimization.
  - `orchestra/notifier` to send e-mail notification per user.
* Update assets:
  - Twitter Bootstrap v3.0.3
  - Javie JavaScript Library v1.1.2

## Version 2.0 {#v2-0}

### v2.0.24@dev {#v2-0-24}

* Remove invalid/irrelevant filters.
* Update assets:
  - Twitter Bootstrap v3.0.3

### v2.0.23 {#v2-0-23}

* Automatically login the user after resetting the password.
* Refactor call to `Orchestra\Extension\RouteGenerator` based on changes to orchestra/extension component.
* Completely remove `Orchestra\Foundation\Services` namespace.
* Move welcome message to `orchestra/foundation::dashboard._welcome` partial.
* Add components:
  - `orchestra/optimize`
  - `orchestra/debug`
* Update assets:
  - Twitter Bootstrap v3.0.2

### v2.0.22 {#v2-0-22}

* Fixed `Javie.Events` usage on triggering switcher event.
* Manage Twitter Bootstrap using Composer.
* Implement [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) coding standard.

### v2.0.21 {#v2-0-21}

* Add `orchestra/foundation::layout.extra` layout view, to be used for registration, login and forgot password interface.
* Cast possible integer to string on return `ID` from `User` model.
* Improved pagination support by allowing `$perPage` value to be configurable from the model.
* Use explicit Route method instead of `Route::controller()` to increase route resolving performance.
* Move both presenter and validator instance dependencies inject to Controller construct method.
* Move `Orchestra\Model` to it's own repository, this would allow it to be used with orchestra/auth on project without Orchestra Platform.
* Multiple namespace refactors.

### v2.0.20 {#v2-0-20}

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

### v2.0.19 {#v2-0-19}

* Re-enable reset password e-mail to be sent using configurable e-mail dispatcher (either direct send or queue).
* Allow customization redirection from logout request.
* Multiple tweaks to CSS.
* Mail configuration are now loaded from the database after installation. This would allow developer to use either `Mail` or `Orchestra\Mail` using the same set of configuration out of the box.
* Allow cancelling SMTP e-mail password after change password is clicked.
* Re-organize `Orchestra\Foundation\Services\Event\AdminMenuHandler`.
* Add missing successful reset password message.
* Refactor presenters to use app container to allow IoC overwrite from application.

### v2.0.18 {#v2-0-18}

* Rework on `Orchestra\Foundation\Mail` to handle inconsistency using both `Mail::queue()` and `Mail::send()`, add new `Orchestra\Mail::push()` option to allow sending based on configuration.
* Both `Orchestra\Mail::send()` and `Orchestra\Mail::queue()` work as you would using `Mail` equivalent.
* Refactor `Orchestra\Foundation\Reminders\PasswordBroker` to force send email directly even if queue is enabled. This is a limitation with `Illuminate\Support\SerializeClosure` that does support use () to include Closure.
* Fixed messages when registration email is sent using queue, instead of showing failed to send.
* Improve user searching with new `Orchestra\Support\Str::searchable()` API.

### v2.0.17 {#v2-0-17}

* Add `orchestra/translation`.
* Improved asset management especially for Twitter Bootstrap.
* Deprecate and remove `subMenu` usage of Navbar Decorator.
* Fixed request to `jquery.min.map` cause 500 errors.
* Separate large view into partial especially on `extensions` and `resources` route for easier theming.
* Fixed some regression bug.

### v2.0.16 {#v2-0-16}

* Update to Twitter Bootstrap v3.0.0.
* Slightly improved Navbar Decorator.

### v2.0.15 {#v2-0-15}

* Add padding to `.navbar a-navbar.brand` CSS.
* Update Bootstrap 3.0-RC2.
* `Orchestra\Foundation\Reminders\PasswordBroker` should extends `Illuminate\Auth\Reminders\PasswordBroker`.
* Fixed CSS styling based on Bootstrap 3-RC2 changes.
* Fixed inconsistent form styling on reset password page.

### v2.0.14 {#v2-0-14}

* Update Twitter Bootstrap 3.
* Change footer to show "Powered by Orchestra Platform" instead of a copyright.
* Tweak installation message on Auth usage.
* Convert prefered function to use `Orchestra\Auth\Acl\Fluent::attach()` instead of `Orchestra\Auth\Acl\Fluent::fill()`.

### v2.0.13 {#v2-0-13}

* Replace deprecated call to `Orchestra\Extension::isActive()` and instead use `Orchestra\Extension::activated()`.
* Replace call to `handles('orchestra/foundation::*')` to `handles('orchestra::*')`.
* Small improvement to migration process during installation.
* Add italian translation.
* Revert alias and provides in `Orchestra\Foundation\Services\TestCase`.
* Add `Orchestra\Foundation\Services\ApplicationTestCase`.
* Add testcase for `Orchestra\Foundation\Routing\CredentialController`.
* Fixed CSS issue on create/update User using Select2.

### v2.0.12 {#v2-0-12}

* Update to Twitter Bootstrap 3.0.0-RC1.
* Clean-up CSS, JavaScript as well as HTML to match Bootstrap 3.

### v2.0.11 {#v2-0-11}

* Fixed typo to `Orchestra\Extension::isActive()`.
* Add safe mode notification when running from safe mode.
* Docblock improvement.

### v2.0.10 {#v2-0-10}

* Update Bootstrap 3.
* Remove requirement to use `Illuminate\Support\Fluent` on `Orchestra\Foundation\Services\Validation\UserAccount`.
* Add client-side JavaScript event on each page load.
* Code improvements.
* Fixed `mkdir(): Permission denied` while uploading extension asset using FTP Publisher.

### v2.0.9 {#v2-0-9}

* Rename `Orchestra\Foundation\Site::localtime()` to `Orchestra\Foundation\Site::toLocalTime()`.
* Fixed date is not appended when attaching role to a user, add `withTimestamps()` options to both `Orchestra\Model\User` and `Orchestra\Model\Role`.
* Add `Orchestra\Foundation\Site::fromLocalTime()` to convert time from local to what set in `"app.timezone"` config.
* Add `Orchestra\Foundation\Application::locate()` to return relative path to packages/app.

### v2.0.8 {#v2-0-8}

* Optimize use of `orchestra/foundation::layout.widgets.header` view.
* Create table and form view for `Orchestra\Html\Table` and `Orchestra\Html\Form`.
* Update Bootstrap 3 files.

### v2.0.7 {#v2-0-7}

* Allow `Orchestra\Foundation\Services\UserMetaRepository` to look for data from eloquent before resolving to default.
* Fixed a bug where no roles is assigned to registered user.
* Improved the base grid system to be more readable.
* Multiple bugfixes to the UX and CSS.

### v2.0.6 {#v2-0-6}

* Tweak Resources menu to be not shown when all resources are hidden.
* Fixed unable to use Publisher FTP since `Illuminate\Filesystem\Filesystem::makeDirectory()` throws PHP error when creating directory failed.

### v2.0.5 {#v2-0-5}

* Move `Orchestra\Services` to `Orchestra\Foundation\Services`.
* Move `Orchestra\Routing` to `Orchestra\Foundation\Routing`.
* All classes are mapped using PSR-0.
* Improved default theme.
* Fixed CSS styling on FTP publisher page.

### v2.0.4 {#v2-0-4}

* Allow guest user to access resources if ACL permit.
* Fixed CSS on User search form.

### v2.0.3 {#v2-0-3}

* Add `@placeholder("orchestra.resources: {name}")`.
* Add data-id attributes to edit and delete link on users page.

### v2.0.2 {#v2-0-2}

* Fixed unable to use `"orchestra.saving: extension.{name}"` event.
* Implement `"orchestra.validate: extension.{name}"` and `Orchestra\Services\Validation\Extension`.
* Fixed regression bug with new implementation on `Orchestra\Support\Validator`.

### v2.0.1 {#v2-0-1}

* Tweak Users search form CSS to match Bootstrap 3.
* Fixed a bug where Extension's handles configuration is not accessible.

### v2.0.0 {#v2-0-0}

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
