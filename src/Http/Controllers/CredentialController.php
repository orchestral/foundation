<?php namespace Orchestra\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\Authenticatable;
use Orchestra\Foundation\Processor\AuthenticateUser;
use Orchestra\Foundation\Processor\DeauthenticateUser;
use Orchestra\Contracts\Auth\Command\ThrottlesLogins as ThrottlesCommand;
use Orchestra\Contracts\Auth\Listener\ThrottlesLogins as ThrottlesListener;
use Orchestra\Contracts\Auth\Listener\AuthenticateUser as AuthenticateListener;
use Orchestra\Contracts\Auth\Listener\DeauthenticateUser as DeauthenticateListener;

class CredentialController extends AdminController implements AuthenticateListener, DeauthenticateListener, ThrottlesListener
{
    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function setupMiddleware()
    {
        $this->middleware('orchestra.guest', ['only' => ['index', 'login']]);
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
    public function login(Request $request, AuthenticateUser $authenticate)
    {
        $input = $request->all();
        $input['_ip'] = $request->ip();

        return $authenticate->login($this, $input);
    }

    /**
     * Logout the user.
     *
     * DELETE (:bundle)/login
     *
     * @return mixed
     */
    public function logout(DeauthenticateUser $deauthenticate)
    {
        return $deauthenticate->logout($this);
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
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
     * @param  int  $seconds
     *
     * @return mixed
     */
    public function sendLockoutResponse($errors, $seconds)
    {
        messages('error', trans('passwords.throttle', ['seconds' => $seconds]));

        return $this->redirectWithErrors(handles('orchestra::login'), $errors);
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
