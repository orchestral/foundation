<?php namespace Orchestra\Foundation\Http\Controllers\Account;

use Orchestra\Contracts\Foundation\Listener\Account\User;
use Orchestra\Foundation\Http\Controllers\AdminController;

abstract class Controller extends AdminController implements User
{
    /**
     * Setup controller filters.
     *
     * @return void
     */
    protected function setupFilters()
    {
        $this->middleware('orchestra.auth');
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
