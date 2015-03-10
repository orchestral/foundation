<?php namespace Orchestra\Foundation\Providers;

use Orchestra\Support\Providers\FilterServiceProvider as ServiceProvider;

class FilterServiceProvider extends ServiceProvider
{
    /**
     * All available route filters.
     *
     * @var array
     */
    protected $filters = [
        'orchestra.auth'        => 'Orchestra\Foundation\Http\Filters\Authenticate',
        'orchestra.csrf'        => 'Orchestra\Foundation\Http\Filters\VerifyCsrfToken',
        'orchestra.guest'       => 'Orchestra\Foundation\Http\Filters\IsGuest',
        'orchestra.installable' => 'Orchestra\Foundation\Http\Filters\CanBeInstalled',
        'orchestra.installed'   => 'Orchestra\Foundation\Http\Filters\IsInstalled',
        'orchestra.manage'      => 'Orchestra\Foundation\Http\Filters\CanManage',
        'orchestra.registrable' => 'Orchestra\Foundation\Http\Filters\IsRegistrable',
    ];
}
