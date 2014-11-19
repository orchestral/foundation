<?php namespace Orchestra\Foundation\Routing\Account;

use Illuminate\Support\Facades\Input;
use Orchestra\Foundation\Processor\Account\PasswordUpdater as Processor;
use Orchestra\Foundation\Contracts\Listener\Account\PasswordUpdater as Listener;

class PasswordUpdaterController extends Controller implements Listener
{
    /**
     * Construct Account Controller to allow user to update own profile.
     * Only authenticated user should be able to access this controller.
     *
     * @param  \Orchestra\Foundation\Processor\Account\PasswordUpdater  $processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;

        parent::__construct();
    }

    /**
     * Edit change password page.
     *
     * GET (:orchestra)/account/password
     *
     * @return mixed
     */
    public function edit()
    {
        return $this->processor->edit($this);
    }

    /**
     * POST Edit change password.
     *
     * POST (:orchestra)/account/password
     *
     * @return mixed
     */
    public function update()
    {
        return $this->processor->update($this, Input::all());
    }

    /**
     * Response to show user password.
     *
     * @param  array  $data
     * @return mixed
     */
    public function showPasswordChanger(array $data)
    {
        set_meta('title', trans("orchestra/foundation::title.account.password"));

        return view('orchestra/foundation::account.password', $data);
    }

    /**
     * Response when validation on change password failed.
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
     * @return mixed
     */
    public function updatePasswordFailedValidation($errors)
    {
        return $this->redirectWithErrors(handles('orchestra::account/password'), $errors);
    }

    /**
     * Response when verify current password failed.
     *
     * @return mixed
     */
    public function verifyCurrentPasswordFailed()
    {
        $message = trans('orchestra/foundation::response.account.password.invalid');

        return $this->redirectWithMessage(handles('orchestra::account/password'), $message, 'error');
    }

    /**
     * Response when update password failed.
     *
     * @param  array  $error
     * @return mixed
     */
    public function updatePasswordFailed(array $error)
    {
        $message = trans('orchestra/foundation::response.db-failed', $error);

        return $this->redirectWithMessage(handles('orchestra::account/password'), $message, 'error');
    }

    /**
     * Response when update password succeed.
     *
     * @return mixed
     */
    public function passwordUpdated()
    {
        $message = trans('orchestra/foundation::response.account.password.update');

        return $this->redirectWithMessage(handles('orchestra::account/password'), $message);
    }
}
