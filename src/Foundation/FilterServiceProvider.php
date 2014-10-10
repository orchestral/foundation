<?php namespace Orchestra\Foundation;

class FilterServiceProvider extends Providers\FilterServiceProvider
{
    /**
     * All available route filters.
     *
     * @var array
     */
    protected $filters = [
        'orchestra.auth' => 'Orchestra\Foundation\Filters\AuthFilter',
        'orchestra.csrf' => 'Orchestra\Foundation\Filters\CsrfFilter',
        'orchestra.installable' => 'Orchestra\Foundation\Filters\InstallableFilter',
        'orchestra.installed' => 'Orchestra\Foundation\Filters\InstalledFilter',
        'orchestra.manage' => 'Orchestra\Foundation\Filters\ManageAuthorizationFilter',
        'orchestra.registrable' => 'Orchestra\Foundation\Filters\RegistrableFilter',
    ];
}
