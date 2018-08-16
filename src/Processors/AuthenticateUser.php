<?php

namespace Orchestra\Foundation\Processors;

use Illuminate\Support\Arr;
use Orchestra\Contracts\Auth\Guard;
use Orchestra\Model\User as Eloquent;
use Orchestra\Contracts\Auth\Command\AuthenticateUser as Command;
use Orchestra\Foundation\Validation\AuthenticateUser as Validator;
use Orchestra\Contracts\Auth\Listener\AuthenticateUser as Listener;
use Orchestra\Contracts\Auth\Command\ThrottlesLogins as ThrottlesCommand;

class AuthenticateUser extends Authenticate implements Command
{
    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Contracts\Auth\Guard  $auth
     * @param  \Orchestra\Foundation\Validation\AuthenticateUser  $validator
     */
    public function __construct(Guard $auth, Validator $validator)
    {
        parent::__construct($auth);

        $this->validator = $validator;
    }

    /**
     * Login a user.
     *
     * @param  \Orchestra\Contracts\Auth\Listener\AuthenticateUser  $listener
     * @param  array  $input
     * @param  \Orchestra\Contracts\Auth\Command\ThrottlesLogins|null  $throttles
     *
     * @return mixed
     */
    public function login(Listener $listener, array $input, ThrottlesCommand $throttles = null)
    {
        $validation = $this->validator->on('login')->with($input);

        // Validate user login, if any errors is found redirect it back to
        // login page with the errors.
        if ($validation->fails()) {
            return $listener->userLoginHasFailedValidation($validation->getMessageBag());
        }

        if ($this->hasTooManyAttempts($throttles)) {
            return $this->handleUserHasTooManyAttempts($listener, $input, $throttles);
        }

        if ($this->authenticate($input)) {
            return $this->handleUserWasAuthenticated($listener, $input, $throttles);
        }

        return $this->handleUserFailedAuthentication($listener, $input, $throttles);
    }

    /**
     * Authenticate the user.
     *
     * @param  array  $input
     *
     * @return bool
     */
    protected function authenticate(array $input)
    {
        $remember = (($input['remember'] ?? 'no') === 'yes');

        $data = Arr::except($input, ['remember']);

        // We should now attempt to login the user using Auth class. If this
        // failed simply return false.
        return $this->auth->attempt($data, $remember);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Orchestra\Contracts\Auth\Listener\AuthenticateUser  $listener
     * @param  array  $input
     * @param  \Orchestra\Contracts\Auth\Command\ThrottlesLogins|null  $throttles
     *
     * @return mixed
     */
    protected function handleUserWasAuthenticated(Listener $listener, array $input, ThrottlesCommand $throttles = null)
    {
        if ($throttles) {
            $throttles->clearLoginAttempts();
        }

        return $listener->userHasLoggedIn($this->verifyWhenFirstTimeLogin($this->getUser()));
    }

    /**
     * Send the response after the user has too many attempts.
     *
     * @param  \Orchestra\Contracts\Auth\Listener\AuthenticateUser  $listener
     * @param  array  $input
     * @param  \Orchestra\Contracts\Auth\Command\ThrottlesLogins|null  $throttles
     *
     * @return mixed
     */
    protected function handleUserHasTooManyAttempts(Listener $listener, array $input, ThrottlesCommand $throttles = null)
    {
        $throttles->incrementLoginAttempts();
        $throttles->fireLockoutEvent();

        return $listener->sendLockoutResponse($input, $throttles->getSecondsBeforeNextAttempts());
    }

    /**
     * Send the response after the user failed authentication.
     *
     * @param  \Orchestra\Contracts\Auth\Listener\AuthenticateUser  $listener
     * @param  array  $input
     * @param  \Orchestra\Contracts\Auth\Command\ThrottlesLogins|null  $throttles
     *
     * @return mixed
     */
    protected function handleUserFailedAuthentication(Listener $listener, array $input, ThrottlesCommand $throttles = null)
    {
        if ($throttles) {
            $throttles->incrementLoginAttempts();
        }

        return $listener->userLoginHasFailedAuthentication($input);
    }

    /**
     * Check if user has too many attempts.
     *
     * @param  \Orchestra\Contracts\Auth\Command\ThrottlesLogins|null  $throttles
     *
     * @return bool
     */
    protected function hasTooManyAttempts(ThrottlesCommand $throttles = null)
    {
        return $throttles && $throttles->hasTooManyLoginAttempts();
    }

    /**
     * Verify user account if has not been verified, other this should
     * be ignored in most cases.
     *
     * @param  \Orchestra\Model\User  $user
     *
     * @return \Orchestra\Model\User
     */
    protected function verifyWhenFirstTimeLogin(Eloquent $user)
    {
        if ((int) $user->getAttribute('status') === Eloquent::UNVERIFIED) {
            $user->activate()->save();
        }

        return $user;
    }

    /**
     * Login a user.
     *
     * @param  \Orchestra\Contracts\Auth\Listener\AuthenticateUser  $listener
     * @param  array  $input
     * @param  \Orchestra\Contracts\Auth\Command\ThrottlesLogins|null  $throttles
     *
     * @return mixed
     */
    public function __invoke(Listener $listener, array $input, ThrottlesCommand $throttles = null)
    {
        return $this->login($listener, $input, $throttles);
    }
}
