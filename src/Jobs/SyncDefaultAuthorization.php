<?php

namespace Orchestra\Foundation\Job;

use Illuminate\Contracts\Foundation\Application;
use Orchestra\Contracts\Authorization\Authorization;

class SyncDefaultAuthorization extends Job
{
    /**
     * Re-sync administrator access control.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Orchestra\Contracts\Authorization\Authorization  $acl
     *
     * @return void
     */
    public function handle(Application $app, Authorization $acl)
    {
        $admin = $app->make('orchestra.role')->admin();

        $acl->allow($admin->name, ['Manage Users', 'Manage Orchestra', 'Manage Roles', 'Manage Acl']);
    }
}
