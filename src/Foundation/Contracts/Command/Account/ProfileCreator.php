<?php namespace Orchestra\Foundation\Contracts\Command\Account;

use Orchestra\Foundation\Contracts\Listener\Account\ProfileCreator as Listener;

interface ProfileCreator
{
    /**
     * View registration page.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\Account\ProfileCreator  $listener
     * @return mixed
     */
    public function create(Listener $listener);

    /**
     * Create a new user.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\Account\ProfileCreator  $listener
     * @param  array  $input
     * @return mixed
     */
    public function store(Listener $listener, array $input);
}
