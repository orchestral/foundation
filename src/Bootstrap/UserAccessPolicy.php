<?php

namespace Orchestra\Foundation\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Orchestra\Model\Listeners\UserAccess;
use Orchestra\Model\Observer\Role as RoleObserver;
use Orchestra\Model\Role;

class UserAccessPolicy
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $this->matchCurrentUserToRoles($app);

        $this->attachAccessPolicyEvents($app);
    }

    /**
     * Match current user to roles.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
     * @return void
     */
    protected function matchCurrentUserToRoles(Application $app)
    {
        $app->make('events')->listen('orchestra.auth: roles', UserAccess::class);
    }

    /**
     * Attach access policy events.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
     * @return void
     */
    protected function attachAccessPolicyEvents(Application $app)
    {
        // Orchestra Platform should be able to watch any changes to Role model
        // and sync the information to "orchestra.acl".
        Role::observe($app->make(RoleObserver::class));

        // Orchestra Platform should be able to determine admin and member roles
        // dynamically.
        Role::setDefaultRoles(\config('orchestra/foundation::roles'));
    }
}
