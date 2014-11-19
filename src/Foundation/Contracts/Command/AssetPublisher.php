<?php namespace Orchestra\Foundation\Contracts\Command;

use Orchestra\Foundation\Contracts\Listener\AssetPublishing as Listener;

interface AssetPublisher
{
    /**
     * Run publishing if possible.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\AssetPublishing  $listener
     * @return mixed
     */
    public function executeAndRedirect(Listener $listener);

    /**
     * Publish process.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\AssetPublishing  $listener
     * @param  array $input
     * @return mixed
     */
    public function publish(Listener $listener, array $input);
}
