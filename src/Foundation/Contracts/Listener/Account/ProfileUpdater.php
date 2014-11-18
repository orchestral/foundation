<?php namespace Orchestra\Foundation\Contracts\Listener\Account;

interface ProfileUpdater extends User
{
    /**
     * Response to show user profile changer.
     *
     * @param  array  $data
     * @return mixed
     */
    public function showProfileChanger(array $data);

    /**
     * Response when validation on update profile failed.
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
     * @return mixed
     */
    public function updateProfileFailedValidation($errors);

    /**
     * Response when update profile failed.
     *
     * @param  array  $errors
     * @return mixed
     */
    public function updateProfileFailed(array $errors);

    /**
     * Response when update profile succeed.
     *
     * @return mixed
     */
    public function profileUpdated();
}
