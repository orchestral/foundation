<?php

namespace Orchestra\Foundation\Observers;

use Orchestra\Foundation\Auth\User;

class UserObserver
{
    /**
     * On created observer.
     *
     * @param  \Orchestra\Foundation\Auth\User  $user
     *
     * @return void
     */
    public function created(User $user): void
    {
        $roleIds = [];

        if ($user->relationLoaded('roles')) {
            $roleIds = $user->getRelation('roles')->pluck('id')->all();
        }

        if (empty($roleIds)) {
            $roleIds = [\config('orchestra/foundation::roles.member', 2)];
        }

        $user->roles()->sync($roleIds);
    }
}
