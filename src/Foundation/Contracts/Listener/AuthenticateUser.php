<?php namespace Orchestra\Foundation\Contracts\Listener;

use Illuminate\Contracts\Auth\Authenticatable;

interface AuthenticateUser
{
    /**
     * Response to user log-in trigger failed validation .
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
     * @return mixed
     */
    public function userLoginHasFailedValidation($errors);

    /**
     * Response to user log-in trigger has failed authentication.
     *
     * @param  array  $input
     * @return mixed
     */
    public function userLoginHasFailedAuthentication(array $input);

    /**
     * Response to user has logged in successfully.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return mixed
     */
    public function userHasLoggedIn(Authenticatable $user);

    /**
     * Response to user has logged out successfully.
     *
     * @return mixed
     */
    public function userHasLoggedOut();
}
