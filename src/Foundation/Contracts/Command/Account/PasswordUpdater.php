<?php namespace Orchestra\Foundation\Contracts\Command\Account;

use Orchestra\Foundation\Contracts\Listener\Account\PasswordUpdater as Listener;

interface PasswordUpdater
{
    /**
     * Get password information.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\Account\PasswordUpdater  $listener
     * @return mixed
     */
    public function edit(Listener $listener);

    /**
     * Update password information.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\Account\PasswordUpdater  $listener
     * @param  array  $input
     * @return mixed
     */
    public function update(Listener $listener, array $input);
}
