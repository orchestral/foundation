<?php

use Illuminate\Support\Facade\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Orchestra\Support\Facades\App;

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application.
|
*/

Route::filter('orchestra.auth', function ($route, $request, $value = null)
{
	if (Auth::guest())
	{
		return Redirect::guest(handles('orchestra/foundation::login'));
	}
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('orchestra.guest', function ($route, $request, $value = null)
{
	if ( ! Auth::guest())
	{
		return Redirect::to(handles('orchestra/foundation::/'));
	}
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('orchestra.csrf', function ($route, $request, $value = null)
{
	// In most case the application already has one, however it might behave 
	// differently or deleted by the user. To avoid un-expected behaviour 
	// the same functionality is duplicated.
	
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/*
|--------------------------------------------------------------------------
| ACL Filter
|--------------------------------------------------------------------------
|
| The ACL filter would check against our RBAC metric to ensure that only 
| user with the right authorization can access certain part of the 
| application.
|
*/

Route::filter('orchestra.manage', function ($route, $request, $value = 'orchestra')
{
	if ( ! App::acl()->can("manage-{$value}"))
	{
		$redirect = (Auth::guest() ? 'login' : '/');
		
		Redirect::to(handles("orchestra/foundation::{$redirect}"));
	}
});

/*
|--------------------------------------------------------------------------
| Registrable Filter
|--------------------------------------------------------------------------
|
| The Registrable filter is use specifically for register routing, this is 
| to ensure that the routing would only be accessible if the setting enable 
| user registration.
|
*/

Route::filter('orchestra.registrable', function ($route, $request, $value = null)
{
	if ( ! memorize('site.registrable', false)) App::abort(404);
});

/*
|--------------------------------------------------------------------------
| Installation Filter
|--------------------------------------------------------------------------
|
| The installation filter determine the state of Orchestra Platform whether 
| installation has been successful.
|
*/

Route::filter('orchestra.installable', function ($route, $request, $value = null)
{
	if (App::make('orchestra.installed') === false)
	{
		return Redirect::to(handles('orchestra/foundation::install'));
	}
});

Route::filter('orchestra.installed', function ($route, $request, $value = null)
{
	if (App::make('orchestra.installed') === true)
	{
		return Redirect::to(handles('orchestra/foundation::/'));
	}
});
