<?php

Route::group(array('prefix' => Config::get('orchestra/foundation::handles', 'orchestra')), function ()
{
	Route::controller('installer', 'Orchestra\Foundation\Controllers\InstallController');
});