<?php

namespace Orchestra\Foundation\Processor;

use Orchestra\Contracts\Auth\Command\DeauthenticateUser as Command;
use Orchestra\Contracts\Auth\Listener\DeauthenticateUser as Listener;

class DeauthenticateUser extends Authenticate implements Command
{
    /**
     * Logout a user.
     *
     * @param  \Orchestra\Contracts\Auth\Listener\DeauthenticateUser  $listener
     *
     * @return mixed
     */
    public function logout(Listener $listener)
    {
        $this->auth->logout();

        return $listener->userHasLoggedOut();
    }
}
