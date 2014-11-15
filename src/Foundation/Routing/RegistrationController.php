<?php namespace Orchestra\Foundation\Routing;

use Orchestra\Support\Facades\Meta;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Orchestra\Support\Facades\Messages;
use Illuminate\Support\Facades\Redirect;
use Orchestra\Foundation\Processor\Registration as RegistrationProcessor;

class RegistrationController extends AdminController
{
    /**
     * Registration Controller routing. It should only be accessible if
     * registration is allowed through the setting.
     *
     * @param  \Orchestra\Foundation\Processor\Registration  $processor
     */
    public function __construct(RegistrationProcessor $processor)
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
    public function index()
    {
        return $this->processor->index($this);
    }

    /**
     * Create a new user.
     *
     * POST (:orchestra)/register
     *
     * @return mixed
     */
    public function create()
    {
        return $this->processor->create($this, Input::all());
    }

    /**
     * Response when show registration page succeed.
     *
     * @param  array  $data
     * @return mixed
     */
    public function indexSucceed(array $data)
    {
        Meta::set('title', trans('orchestra/foundation::title.register'));

        return View::make('orchestra/foundation::credential.register', $data);
    }

    /**
     * Response when create a user failed validation.
     *
     * @param  mixed  $validation
     * @return mixed
     */
    public function createValidationFailed($validation)
    {
        return $this->redirectWithErrors(handles('orchestra::register'), $validation);
    }

    /**
     * Response when create a user failed.
     *
     * @param  array  $error
     * @return mixed
     */
    public function createFailed(array $error)
    {
        $message = trans('orchestra/foundation::response.db-failed', $error);

        return $this->redirectWithMessage(handles('orchestra::register'), $message, 'error')->withInput();
    }

    /**
     * Response when create a user succeed but unable to notify the user.
     *
     * @return mixed
     */
    public function createSucceedWithoutNotification()
    {
        Messages::add('success', trans("orchestra/foundation::response.users.create"));
        Messages::add('error', trans('orchestra/foundation::response.credential.register.email-fail'));

        return Redirect::intended(handles('orchestra::login'));
    }

    /**
     * Response when create a user succeed with notification.
     *
     * @return mixed
     */
    public function createSucceed()
    {
        Messages::add('success', trans("orchestra/foundation::response.users.create"));
        Messages::add('success', trans('orchestra/foundation::response.credential.register.email-send'));

        return Redirect::intended(handles('orchestra::login'));
    }
}
