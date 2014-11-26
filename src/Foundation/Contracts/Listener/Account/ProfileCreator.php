<?php namespace Orchestra\Foundation\Contracts\Listener\Account;

interface ProfileCreator
{
    /**
     * Response when show registration page succeed.
     *
     * @param  array  $data
     * @return mixed
     */
    public function showProfileCreator(array $data);

    /**
     * Response when create a user failed validation.
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
     * @return mixed
     */
    public function createProfileFailedValidation($errors);

    /**
     * Response when create a user failed.
     *
     * @param  array  $errors
     * @return mixed
     */
    public function createProfileFailed(array $errors);

    /**
     * Response when create a user succeed but unable to notify the user.
     *
     * @return mixed
     */
    public function profileCreatedWithoutNotification();

    /**
     * Response when create a user succeed with notification.
     *
     * @return mixed
     */
    public function profileCreated();
}
