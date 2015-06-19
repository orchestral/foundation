<?php namespace Orchestra\Foundation\Bootstrap;

use Orchestra\Model\Role;
use Orchestra\Model\User;
use Illuminate\Contracts\Foundation\Application;
use Orchestra\Model\Observer\Role as RoleObserver;

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
        $app->make('events')->listen('orchestra.auth: roles', function (User $user = null) {
            // When user is null, we should expect the roles is not available.
            // Therefore, returning null would propagate any other event listeners
            // (if any) to try resolve the roles.
            if (is_null($user)) {
                return;
            }

            $roles = $user->getRoles();

            return $roles;
        });
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
        Role::setDefaultRoles($app->make('config')->get('orchestra/foundation::roles'));
    }
}
