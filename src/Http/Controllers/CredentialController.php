<?php namespace Orchestra\Foundation\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\Authenticatable;
use Orchestra\Foundation\Processor\AuthenticateUser as Processor;
use Orchestra\Contracts\Auth\Listener\AuthenticateUser as Listener;

class CredentialController extends AdminController implements Listener
{
    /**
     * Authentication/Credential controller routing.
     *
     * @param \Orchestra\Foundation\Processor\AuthenticateUser  $processor
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
        $this->beforeFilter('orchestra.guest', ['only' => ['index', 'login']]);
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
        set_meta('title', trans('orchestra/foundation::title.login'));

        return view('orchestra/foundation::credential.login');
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
        $input = Arr::only(Input::all(), ['email', 'password', 'remember']);

        return $this->processor->login($this, $input);
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
     * Response to user log-in trigger failed validation .
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
     *
     * @return mixed
     */
    public function userLoginHasFailedValidation($errors)
    {
        return $this->redirectWithErrors(handles('orchestra::login'), $errors);
    }

    /**
     * Response to user log-in trigger has failed authentication.
     *
     * @param  array  $input
     *
     * @return mixed
     */
    public function userLoginHasFailedAuthentication(array $input)
    {
        $message = trans('orchestra/foundation::response.credential.invalid-combination');

        return $this->redirectWithMessage(handles('orchestra::login'), $message, 'error')->withInput();
    }

    /**
     * Response to user has logged in successfully.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     *
     * @return mixed
     */
    public function userHasLoggedIn(Authenticatable $user)
    {
        messages('success', trans('orchestra/foundation::response.credential.logged-in'));

        return Redirect::intended(handles('orchestra::/'));
    }

    /**
     * Response to user has logged out successfully.
     *
     * @return mixed
     */
    public function userHasLoggedOut()
    {
        messages('success', trans('orchestra/foundation::response.credential.logged-out'));

        return Redirect::intended(handles(Input::get('redirect', 'orchestra::login')));
    }
}
