<?php namespace Orchestra\Foundation\Routing\Account;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Orchestra\Foundation\Routing\AdminController;
use Orchestra\Foundation\Processor\Account\ProfileCreator as Processor;
use Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator as Listener;

class ProfileCreatorController extends AdminController implements Listener
{
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
     * Setup controller filters.
     *
     * @return void
     */
    protected function setupFilters()
    {
        $this->beforeFilter('orchestra.registrable');
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
        return $this->redirectWithErrors(handles('orchestra::register'), $errors);
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

        return $this->redirect(handles('orchestra::register'))->withInput();
    }

    /**
     * Response when create a user succeed but unable to notify the user.
     *
     * @return mixed
     */
    public function profileCreatedWithoutNotification()
    {
        messages('success', trans("orchestra/foundation::response.users.create"));
        messages('error', trans('orchestra/foundation::response.credential.register.email-fail'));

        return Redirect::intended(handles('orchestra::login'));
    }

    /**
     * Response when create a user succeed with notification.
     *
     * @return mixed
     */
    public function profileCreated()
    {
        messages('success', trans("orchestra/foundation::response.users.create"));
        messages('success', trans('orchestra/foundation::response.credential.register.email-send'));

        return Redirect::intended(handles('orchestra::login'));
    }
}
