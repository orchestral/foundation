<?php namespace Orchestra\Foundation;

use Orchestra\Support\Providers\FilterServiceProvider as ServiceProvider;

class FilterServiceProvider extends ServiceProvider
{
    /**
     * All available route filters.
     *
     * @var array
     */
    protected $filters = [
        'orchestra.auth' => 'Orchestra\Foundation\Filters\Authenticate',
        'orchestra.guest' => 'Orchestra\Foundation\Filters\IsGuest',
        'orchestra.installable' => 'Orchestra\Foundation\Filters\CanBeInstalled',
        'orchestra.installed' => 'Orchestra\Foundation\Filters\IsInstalled',
        'orchestra.manage' => 'Orchestra\Foundation\Filters\CanManage',
        'orchestra.registrable' => 'Orchestra\Foundation\Filters\IsRegistrable',
    ];
}
