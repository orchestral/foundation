<?php

namespace Orchestra\Foundation\Listeners;

use Orchestra\Model\User;

class UserAccess
{
    /**
     * Match current user to roles.
     *
     * @param  \Orchestra\Model\User|null  $user
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function handle(User $user = null)
    {
        // When user is null, we should expect the roles is not available.
        // Therefore, returning null would propagate any other event
        // listeners (if any) to try resolve the roles.
        if (is_null($user)) {
            return;
        }

        $roles = $user->getRoles();

        return $roles;
    }
}
