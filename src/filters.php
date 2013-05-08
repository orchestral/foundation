<?php

Route::filter('orchestra.auth', function ($route, $request, $value = null)
{
	// Redirect the user to login page if user is not logged in.
	if (Auth::guest()) return Redirect::to(handles('orchestra/foundation::login'));
});

Route::filter('orchestra.logged', function ($route, $request, $value = null)
{
	// Redirect the user to dashboard page if user is logged in.
	if ( ! Auth::guest()) return Redirect::to(handles('orchestra/foundation::/'));
});

Route::filter('orchestra.manage', function ($route, $request, $value = 'orchestra')
{
	// Redirect the user to login page if user is not logged in.
	if ( ! Orchestra\App::acl()->can("manage-{$value}"))
	{
		if (Auth::guest())
		{
			return Redirect::to(handles('orchestra/foundation::login'));
		}

		return Redirect::to(handles('orchestra/foundation::/'));
	}
});

Route::filter('orchestra.registrable', function ($route, $request, $value = null)
{
	if ( ! memorize('site.registrable', false)) App::abort(404);
});

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

Route::filter('orchestra.csrf', function ($route, $request, $value = null)
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
