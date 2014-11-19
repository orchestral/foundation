<?php namespace Orchestra\Foundation\Contracts\Listener\Account;

interface PasswordResetLink
{
    /**
     * Response when request password failed on validation.
     *
     * @param  \Illuminate\Support\MessageBag|array $errors
     * @return mixed
     */
    public function resetLinkFailedValidation($errors);

    /**
     * Response when request reset password failed.
     *
     * @param  string $response
     * @return mixed
     */
    public function resetLinkFailed($response);

    /**
     * Response when request reset password succeed.
     *
     * @param  string $response
     * @return mixed
     */
    public function resetLinkSent($response);
}
