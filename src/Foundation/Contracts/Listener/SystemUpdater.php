<?php namespace Orchestra\Foundation\Contracts\Listener;

interface SystemUpdater
{
    /**
     * Response when update Orchestra Platform succeed.
     *
     * @return mixed
     */
    public function systemHasUpdated();
}
