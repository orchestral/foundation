<?php namespace Orchestra\Foundation\Contracts\Command\Account;

use Orchestra\Foundation\Contracts\Listener\Account\PasswordReset;
use Orchestra\Foundation\Contracts\Listener\Account\PasswordResetLink;

interface PasswordBroker
{
    /**
     * Request to reset password.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\Account\PasswordResetLink  $listener
     * @param  array  $input
     * @return mixed
     */
    public function store(PasswordResetLink $listener, array $input);

    /**
     * Reset the password.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\Account\PasswordReset  $listener
     * @param  array  $input
     * @return mixed
     */
    public function update(PasswordReset $listener, array $input);
}
