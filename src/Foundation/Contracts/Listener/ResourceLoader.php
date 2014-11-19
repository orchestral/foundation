<?php namespace Orchestra\Foundation\Contracts\Listener;

interface ResourceLoader
{
    /**
     * Response when show resources lists succeed.
     *
     * @param  array $data
     * @return mixed
     */
    public function showResourcesList(array $data);

    /**
     * Response when load resource succeed.
     *
     * @param  array $data
     * @return mixed
     */
    public function onRequestSucceed(array $data);
}