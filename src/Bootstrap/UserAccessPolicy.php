<?php namespace Orchestra\Foundation\Bootstrap;

use Orchestra\Model\Role;
use Illuminate\Contracts\Foundation\Application;

class UserAccessPolicy
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
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
     * @return void
     */
    protected function matchCurrentUserToRoles(Application $app)
    {
        $app['events']->listen('orchestra.auth: roles', function ($user) {
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
     * @return void
     */
    protected function attachAccessPolicyEvents(Application $app)
    {
        // Orchestra Platform should be able to watch any changes to Role model
        // and sync the information to "orchestra.acl".
        Role::observe($app->make('Orchestra\Model\Observer\Role'));

        // Orchestra Platform should be able to determine admin and member roles
        // dynamically.
        Role::setDefaultRoles($app['config']->get('orchestra/foundation::roles'));
    }
}
