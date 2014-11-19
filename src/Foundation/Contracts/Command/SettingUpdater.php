<?php namespace Orchestra\Foundation\Contracts\Command;

use Orchestra\Foundation\Contracts\Listener\SettingUpdater as Listener;

interface SettingUpdater
{
    /**
     * View setting page.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\SettingUpdater  $listener
     * @return mixed
     */
    public function edit(Listener $listener);

    /**
     * Update setting.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\SettingUpdater  $listener
     * @param  array $input
     * @return mixed
     */
    public function update(Listener $listener, array $input);
}
