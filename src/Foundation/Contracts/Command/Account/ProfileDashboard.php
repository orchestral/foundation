<?php namespace Orchestra\Foundation\Contracts\Command\Account;

use Orchestra\Foundation\Contracts\Listener\Account\ProfileDashboard as Listener;

interface ProfileDashboard
{
    /**
     * View dashboard.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\Account\ProfileDashboard $listener
     * @return mixed
     */
    public function show(Listener $listener);
}
