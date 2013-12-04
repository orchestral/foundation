<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Site;
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
    public function getProfile()
    {
        Site::set('title', trans("orchestra/foundation::title.account.profile"));

        return $this->processor->showProfile($this);
    }

    /**
     * POST Edit user account/profile.
     *
     * POST (:orchestra)/account
     *
     * @return Response
     */
    public function postProfile()
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
    public function getPassword()
    {
        Site::set('title', trans("orchestra/foundation::title.account.password"));

        return $this->processor->showPassword($this);
    }

    /**
     * POST Edit change password.
     *
     * POST (:orchestra)/account/password
     *
     * @return Response
     */
    public function postPassword()
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
        return Redirect::to(handles('orchestra::account'))
                ->withInput()
                ->withErrors($validation);
    }

    /**
     * Response when update profile failed.
     *
     * @param  string  $message
     * @return Response
     */
    public function updateProfileFailed($message)
    {
        Messages::add('error', $message);

        return Redirect::to(handles('orchestra::account'));
    }

    /**
     * Response when update profile succeed.
     *
     * @param  string  $message
     * @return Response
     */
    public function updateProfileSucceed($message)
    {
        Messages::add('success', $message);

        return Redirect::to(handles('orchestra::account'));
    }

    /**
     * Response view change password page.
     *
     * @param  array   $data
     * @return Response
     */
    public function showPasswordSucceed(array $data)
    {
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
        return Redirect::to(handles('orchestra::account/password'))->withInput()->withErrors($validation);
    }

    /**
     * Response when update password failed.
     *
     * @param  string  $message
     * @return Response
     */
    public function updatePasswordFailed($message)
    {
        Messages::add('error', $message);

        return Redirect::to(handles('orchestra::account/password'));
    }

    /**
     * Response when update password succeed.
     *
     * @param  string  $message
     * @return Response
     */
    public function updatePasswordSucceed($message)
    {
        Messages::add('success', $message);

        return Redirect::to(handles('orchestra::account/password'));
    }

    /**
     * Response when user verification failed.
     *
     * @return Response
     */
    public function userVerificationFailed()
    {
        return App::abort(500);
    }
}
