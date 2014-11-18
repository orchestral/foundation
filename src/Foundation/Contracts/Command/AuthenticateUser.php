<?php namespace Orchestra\Foundation\Contracts\Command;

use Orchestra\Foundation\Contracts\Listener\AuthenticateUser as Listener;

interface AuthenticateUser
{
    /**
     * Login a user.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\AuthenticateUser $listener
     * @param  array $input
     * @return mixed
     */
    public function login(Listener $listener, array $input);

    /**
     * Logout a user.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\AuthenticateUser $listener
     * @return mixed
     */
    public function logout(Listener $listener);
}
