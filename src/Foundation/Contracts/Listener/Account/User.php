<?php namespace Orchestra\Foundation\Contracts\Listener\Account;

interface User
{
    /**
     * Abort request when user mismatched.
     *
     * @return mixed
     */
    public function abortWhenUserMismatched();
}
