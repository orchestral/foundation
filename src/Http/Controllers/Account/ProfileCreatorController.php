<?php namespace Orchestra\Foundation\Http\Controllers\Account;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Orchestra\Foundation\Traits\RedirectUsers;
use Orchestra\Foundation\Http\Controllers\AdminController;
use Orchestra\Foundation\Processor\Account\ProfileCreator as Processor;
use Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator as Listener;

class ProfileCreatorController extends AdminController implements Listener
{
    use RedirectUsers;

    /**
     * Registration Controller routing. It should only be accessible if
     * registration is allowed through the setting.
     *
     * @param  \Orchestra\Foundation\Processor\Account\ProfileCreator  $processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;

        parent::__construct();
    }

    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function setupMiddleware()
    {
        $this->middleware('orchestra.registrable');
    }

    /**
     * User Registration Page.
     *
     * GET (:orchestra)/register
     *
     * @return mixed
     */
    public function create()
    {
        return $this->processor->create($this);
    }

    /**
     * Create a new user.
     *
     * POST (:orchestra)/register
     *
     * @return mixed
     */
    public function store()
    {
        return $this->processor->store($this, Input::all());
    }

    /**
     * Response when show registration page succeed.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function showProfileCreator(array $data)
    {
        set_meta('title', trans('orchestra/foundation::title.register'));

        return view('orchestra/foundation::credential.register', $data);
    }

    /**
     * Response when create a user failed validation.
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
     *
     * @return mixed
     */
    public function createProfileFailedValidation($errors)
    {
        return $this->redirectWithErrors($this->getRedirectToRegisterPath(), $errors);
    }

    /**
     * Response when create a user failed.
     *
     * @param  array  $errors
     *
     * @return mixed
     */
    public function createProfileFailed(array $errors)
    {
        messages('error', trans('orchestra/foundation::response.db-failed', $errors));

        return $this->redirect($this->getRedirectToRegisterPath())->withInput();
    }

    /**
     * Response when create a user succeed but unable to notify the user.
     *
     * @return mixed
     */
    public function profileCreatedWithoutNotification()
    {
        messages('success', trans('orchestra/foundation::response.users.create'));
        messages('error', trans('orchestra/foundation::response.credential.register.email-fail'));

        return Redirect::intended($this->getRedirectToLoginPath());
    }

    /**
     * Response when create a user succeed with notification.
     *
     * @return mixed
     */
    public function profileCreated()
    {
        messages('success', trans('orchestra/foundation::response.users.create'));
        messages('success', trans('orchestra/foundation::response.credential.register.email-send'));

        return Redirect::intended($this->getRedirectToLoginPath());
    }

    /**
     * Get redirect to register path.
     *
     * @param  string|null  $redirect
     * @return string
     */
    protected function getRedirectToRegisterPath($redirect = null)
    {
        return $this->resolveUserRedirectionPath('orchestra::register', $redirect);
    }

    /**
     * Get redirect to login path.
     *
     * @param  string|null  $redirect
     * @return string
     */
    protected function getRedirectToLoginPath($redirect = null)
    {
        return $this->resolveUserRedirectionPath('orchestra::login', $redirect);
    }
}
