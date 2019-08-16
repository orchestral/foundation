<?php

namespace Orchestra\Foundation\Http\Controllers;

use Laravie\Authen\Authen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\Authenticatable;
use Orchestra\Foundation\Concerns\RedirectUsers;
use Orchestra\Foundation\Processors\AuthenticateUser;
use Orchestra\Foundation\Processors\DeauthenticateUser;
use Orchestra\Contracts\Auth\Command\ThrottlesLogins as ThrottlesCommand;
use Orchestra\Contracts\Auth\Listener\ThrottlesLogins as ThrottlesListener;
use Orchestra\Contracts\Auth\Listener\AuthenticateUser as AuthenticateListener;
use Orchestra\Contracts\Auth\Listener\DeauthenticateUser as DeauthenticateListener;

class CredentialController extends AdminController implements AuthenticateListener, DeauthenticateListener, ThrottlesListener
{
    use RedirectUsers;

    /**
     * Redirect to after login URI.
     *
     * @var string|null
     */
    protected $redirectToPath = null;

    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function onCreate()
    {
        $this->middleware('orchestra.guest', ['only' => ['index', 'show', 'login']]);
        $this->middleware('orchestra.csrf', ['only' => 'logout']);
    }

    /**
     * Login page.
     *
     * GET (:orchestra)/login
     *
     * @return mixed
     */
    public function show()
    {
        \set_meta('title', \trans('orchestra/foundation::title.login'));

        return \view('orchestra/foundation::credential.login');
    }

    /**
     * POST Login the user.
     *
     * POST (:orchestra)/login
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Orchestra\Foundation\Processors\AuthenticateUser  $authenticate
     * @param \Orchestra\Contracts\Auth\Command\ThrottlesLogins  $throttles
     *
     * @return mixed
     */
    public function login(Request $request, AuthenticateUser $authenticate, ThrottlesCommand $throttles)
    {
        $this->redirectToPath = $request->input('redirect');

        $username = Authen::getIdentifierName();

        $input = $request->only([$username, 'password', 'remember']);

        $throttles->setRequest($request)->setLoginKey($username);

        return $authenticate($this, $input, $throttles);
    }

    /**
     * Logout the user.
     *
     * DELETE (:bundle)/login
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Orchestra\Foundation\Processors\DeauthenticateUser  $deauthenticate
     *
     * @return mixed
     */
    public function logout(Request $request, DeauthenticateUser $deauthenticate)
    {
        $this->redirectToPath = $request->input('redirect');

        return $deauthenticate($this);
    }

    /**
     * Response to user log-in trigger failed validation.
     *
     * @param  \Illuminate\Contracts\Support\MessageBag|array  $errors
     *
     * @return mixed
     */
    public function userLoginHasFailedValidation($errors)
    {
        return $this->redirectWithErrors($this->getRedirectToLoginPath(), $errors);
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
        $message = \trans('orchestra/foundation::response.credential.invalid-combination');

        return $this->redirectWithMessage($this->getRedirectToLoginPath(), $message, 'error')->withInput();
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  array  $input
     * @param  int  $seconds
     *
     * @return mixed
     */
    public function sendLockoutResponse(array $input, $seconds)
    {
        $message = \trans('auth.throttle', ['seconds' => $seconds]);

        return $this->redirectWithMessage($this->getRedirectToLoginPath(), $message, 'error')->withInput();
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
        \messages('success', \trans('orchestra/foundation::response.credential.logged-in'));

        return Redirect::intended($this->getRedirectToAuthenticatedPath());
    }

    /**
     * Response to user has logged out successfully.
     *
     * @return mixed
     */
    public function userHasLoggedOut()
    {
        \messages('success', \trans('orchestra/foundation::response.credential.logged-out'));

        return Redirect::intended($this->getRedirectToLoginPath());
    }

    /**
     * Get redirect to login path.
     *
     * @return string
     */
    protected function getRedirectToLoginPath(): string
    {
        return $this->redirectUserTo('login', 'orchestra::login', $this->redirectToPath);
    }

    /**
     * Get redirect to login path.
     *
     * @return string
     */
    protected function getRedirectToAuthenticatedPath(): string
    {
        return $this->redirectUserTo('dashboard', 'orchestra::/', $this->redirectToPath);
    }
}
