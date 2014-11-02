<?php namespace Orchestra\Foundation;

class FilterServiceProvider extends Providers\FilterServiceProvider
{
    /**
     * All available route filters.
     *
     * @var array
     */
    protected $filters = [
        'orchestra.auth' => 'Orchestra\Foundation\Filters\Authenticated',
        'orchestra.csrf' => 'Orchestra\Foundation\Filters\VerifyCsrfToken',
        'orchestra.guest' => 'Orchestra\Foundation\Filters\IsGuest',
        'orchestra.installable' => 'Orchestra\Foundation\Filters\CanBeInstalled',
        'orchestra.installed' => 'Orchestra\Foundation\Filters\IsInstalled',
        'orchestra.manage' => 'Orchestra\Foundation\Filters\CanManage',
        'orchestra.registrable' => 'Orchestra\Foundation\Filters\IsRegistrable',
    ];
}
