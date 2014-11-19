<?php namespace Orchestra\Foundation\Contracts\Command;

use Orchestra\Foundation\Contracts\Listener\SystemUpdater as Listener;

interface SystemUpdater
{
    /**
     * Migrate Orchestra Platform components.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\SystemUpdater  $listener
     * @return mixed
     */
    public function migrate(Listener $listener);
}
