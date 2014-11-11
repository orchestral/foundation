<?php namespace Orchestra\Foundation\Routing;

use Orchestra\Support\Facades\Meta;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Orchestra\Support\Facades\Messages;
use Illuminate\Support\Facades\Redirect;
use Orchestra\Foundation\Processor\Credential as CredentialProcessor;

class CredentialController extends AdminController
{
    /**
     * Authentication/Credential controller routing.
     *
     * @param \Orchestra\Foundation\Processor\Credential   $processor
     */
    public function __construct(CredentialProcessor $processor)
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
        $this->beforeFilter('orchestra.guest', [
            'only' => ['index', 'login'],
        ]);
    }

    /**
     * Login page.
     *
     * GET (:orchestra)/login
     *
     * @return mixed
     */
    public function index()
    {
        Meta::set('title', trans("orchestra/foundation::title.login"));

        return View::make('orchestra/foundation::credential.login');
    }

    /**
     * POST Login the user.
     *
     * POST (:orchestra)/login
     *
     * @return mixed
     */
    public function login()
    {
        return $this->processor->login($this, Input::all());
    }

    /**
     * Logout the user.
     *
     * DELETE (:bundle)/login
     *
     * @return mixed
     */
    public function logout()
    {
        return $this->processor->logout($this);
    }

    /**
     * Response when validation on login failed.
     *
     * @param  object  $validation
     * @return mixed
     */
    public function loginValidationFailed($validation)
    {
        return $this->redirectWithErrors(handles('orchestra::login'), $validation);
    }

    /**
     * Response when login failed.
     *
     * @return mixed
     */
    public function loginFailed()
    {
        $message = trans('orchestra/foundation::response.credential.invalid-combination');

        return $this->redirectWithMessage(handles('orchestra::login'), $message, 'error')->withInput();
    }

    /**
     * Response when login succeed.
     *
     * @return mixed
     */
    public function loginSucceed()
    {
        Messages::add('success', trans('orchestra/foundation::response.credential.logged-in'));

        return Redirect::intended(handles('orchestra::/'));
    }

    /**
     * Response when logout succeed.
     *
     * @return mixed
     */
    public function logoutSucceed()
    {
        Messages::add('success', trans('orchestra/foundation::response.credential.logged-out'));

        return Redirect::intended(handles(Input::get('redirect', 'orchestra::login')));
    }
}
