<?php namespace Orchestra\Foundation\Contracts\Command;

use Orchestra\Foundation\Contracts\Listener\ResourceLoader as Listener;

interface ResourceLoader
{
    /**
     * View list resources page.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\ResourceLoader  $listener
     * @return mixed
     */
    public function index(Listener $listener);

    /**
     * View call a resource page.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\ResourceLoader  $listener
     * @param  string  $request
     * @return mixed
     */
    public function show(Listener $listener, $request);
}
