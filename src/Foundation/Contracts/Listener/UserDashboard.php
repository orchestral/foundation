<?php namespace Orchestra\Foundation\Contracts\Listener;

interface UserDashboard
{
    /**
     * Response to show dashboard.
     *
     * @param  array  $data
     * @return mixed
     */
    public function showDashboard(array $data);
}