<?php namespace Orchestra\Foundation\Contracts\Command;

use Orchestra\Foundation\Contracts\Listener\UserDashboard as Listener;

interface UserDashboard
{
    /**
     * View dashboard.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\UserDashboard $listener
     * @return mixed
     */
    public function show(Listener $listener);
}