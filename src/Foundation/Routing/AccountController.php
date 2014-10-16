<?php namespace Orchestra\Foundation\Routing;

use Orchestra\Support\Facades\Meta;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Orchestra\Foundation\Processor\Account as AccountProcessor;

class AccountController extends AdminController
{
    /**
     * Construct Account Controller to allow user to update own profile.
     * Only authenticated user should be able to access this controller.
     *
     * @param  \Orchestra\Foundation\Processor\Account $processor
     */
    public function __construct(AccountProcessor $processor)
    {
        $this->processor = $processor;

        parent::__construct();
    }

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
     * Edit user account/profile page.
     *
     * GET (:orchestra)/account
     *
     * @return Response
     */
    public function showProfile()
    {
        return $this->processor->showProfile($this);
    }

    /**
     * POST Edit user account/profile.
     *
     * POST (:orchestra)/account
     *
     * @return Response
     */
    public function updateProfile()
    {
        return $this->processor->updateProfile($this, Input::all());
    }

    /**
     * Edit change password page.
     *
     * GET (:orchestra)/account/password
     *
     * @return Response
     */
    public function showPassword()
    {
        return $this->processor->showPassword($this);
    }

    /**
     * POST Edit change password.
     *
     * POST (:orchestra)/account/password
     *
     * @return Response
     */
    public function updatePassword()
    {
        return $this->processor->updatePassword($this, Input::all());
    }

    /**
     * Response view account/profile page.
     *
     * @param  array   $data
     * @return Response
     */
    public function showProfileSucceed(array $data)
    {
        Meta::set('title', trans("orchestra/foundation::title.account.profile"));

        return View::make('orchestra/foundation::account.index', $data);
    }

    /**
     * Response when validation on update profile failed.
     *
     * @param  object  $validation
     * @return Response
     */
    public function updateProfileValidationFailed($validation)
    {
        return $this->redirectWithErrors(handles('orchestra::account'), $validation);
    }

    /**
     * Response when update profile failed.
     *
     * @param  string|null $message
     * @return Response
     */
    public function updateProfileFailed(array $error)
    {
        $message = trans('orchestra/foundation::response.db-failed', $error);

        return $this->redirectWithMessage(handles('orchestra::account'), $message, 'error');
    }

    /**
     * Response when update profile succeed.
     *
     * @return Response
     */
    public function updateProfileSucceed()
    {
        $message = trans('orchestra/foundation::response.account.profile.update');

        return $this->redirectWithMessage(handles('orchestra::account'), $message);
    }

    /**
     * Response view change password page.
     *
     * @param  array   $data
     * @return Response
     */
    public function showPasswordSucceed(array $data)
    {
        Meta::set('title', trans("orchestra/foundation::title.account.password"));

        return View::make('orchestra/foundation::account.password', $data);
    }

    /**
     * Response when validation on change password failed.
     *
     * @param  object  $validation
     * @return Response
     */
    public function updatePasswordValidationFailed($validation)
    {
        return $this->redirectWithErrors(handles('orchestra::account/password'), $validation);
    }

    /**
     * Response when verify current password failed.
     */
    public function verifyCurrentPasswordFailed()
    {
        $message = trans('orchestra/foundation::response.account.password.invalid');

        return $this->redirectWithMessage(handles('orchestra::account/password'), $message, 'error');
    }

    /**
     * Response when update password failed.
     *
     * @param  array   $error
     * @return Response
     */
    public function updatePasswordFailed(array $error)
    {
        $message = trans('orchestra/foundation::response.db-failed', $error);

        return $this->redirectWithMessage(handles('orchestra::account/password'), $message, 'error');
    }

    /**
     * Response when update password succeed.
     *
     * @return Response
     */
    public function updatePasswordSucceed()
    {
        $message = trans('orchestra/foundation::response.account.password.update');

        return $this->redirectWithMessage(handles('orchestra::account/password'), $message);
    }
}
