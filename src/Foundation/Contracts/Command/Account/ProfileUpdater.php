<?php namespace Orchestra\Foundation\Contracts\Command\Account;

use Orchestra\Foundation\Contracts\Listener\Account\ProfileUpdater as Listener;

interface ProfileUpdater
{
    /**
     * Get account/profile information.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\Account\ProfileUpdater  $listener
     * @return mixed
     */
    public function show(Listener $listener);

    /**
     * Update profile information.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\Account\ProfileUpdater  $listener
     * @param  array  $input
     * @return mixed
     */
    public function update(Listener $listener, array $input);
}
