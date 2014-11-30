<?php namespace Orchestra\Foundation\Contracts\Listener\Account;

interface ProfileDashboard
{
    /**
     * Response to show dashboard.
     *
     * @param  array  $data
     * @return mixed
     */
    public function showDashboard(array $data);
}
