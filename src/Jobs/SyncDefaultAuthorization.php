<?php

namespace Orchestra\Foundation\Jobs;

use Orchestra\Contracts\Authorization\Authorization;
use Orchestra\Model\Role;

class SyncDefaultAuthorization extends Job
{
    /**
     * Re-sync administrator access control.
     *
     * @param  \Orchestra\Contracts\Authorization\Authorization  $acl
     *
     * @return void
     */
    public function handle(Authorization $acl)
    {
        $admin = Role::hs()->admin();

        $acl->allow($admin->name, ['Manage Users', 'Manage Orchestra', 'Manage Roles', 'Manage Acl']);
    }
}
