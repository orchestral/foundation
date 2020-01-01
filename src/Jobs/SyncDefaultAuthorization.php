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
        $actions = \config('orchestra/foundation::actions', [
            'Manage Users', 'Manage Orchestra', 'Manage Roles', 'Manage Acl',
        ]);

        $attaches = [];

        foreach ($actions as $action) {
            if (! $acl->actions()->has($action)) {
                $attaches[] = $action;
            }
        }

        if (! empty($attaches)) {
            $acl->actions()->attach($attaches);
        }

        $admin = Role::hs()->admin();

        $acl->allow($admin->name, $actions);
    }
}
