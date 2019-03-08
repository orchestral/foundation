<?php

namespace Orchestra\Foundation\Observers;

use Orchestra\Foundation\Auth\User;

class UserObserver
{
    /**
     * On created observer.
     *
     * @param  \Orchestra\Foundation\Auth\User  $user
     * @return void
     */
    public function created(User $user): void
    {
        $user->roles()->sync([
            \config('orchestra/foundation::roles.member', 2),
        ]);
    }
}
