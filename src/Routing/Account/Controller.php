<?php namespace Orchestra\Foundation\Routing\Account;

use Orchestra\Foundation\Routing\AdminController;
use Orchestra\Contracts\Foundation\Listener\Account\User;

abstract class Controller extends AdminController implements User
{
    /**
     * Setup controller filters.
     *
     * @return void
     */
    protected function setupFilters()
    {
        $this->beforeFilter('orchestra.auth');
    }

    /**
     * Abort request when user mismatched.
     *
     * @return mixed
     */
    public function abortWhenUserMismatched()
    {
        return $this->suspend(500);
    }
}
