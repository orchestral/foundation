<?php namespace Orchestra\Foundation\Contracts\Listener\Account;

interface PasswordReset
{
    /**
     * Response when reset password failed.
     *
     * @param  string  $response
     * @return mixed
     */
    public function passwordResetHasFailed($response);

    /**
     * Response when reset password succeed.
     *
     * @param  string  $response
     * @return mixed
     */
    public function passwordHasReset($response);
}
