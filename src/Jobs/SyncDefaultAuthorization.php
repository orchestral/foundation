<?php

namespace Orchestra\Foundation\Jobs;

use Illuminate\Support\Collection;
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
        $actions = Collection::make(\config('orchestra/foundation::actions', []))
            ->merge([
                'Manage Users', 'Manage Orchestra', 'Manage Roles', 'Manage Acl',
            ])
            ->unique()
            ->values();

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

        $acl->allow($admin->name, $actions->all());
    }
}
