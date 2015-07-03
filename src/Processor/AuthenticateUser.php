<?php namespace Orchestra\Foundation\Processor;

use Illuminate\Support\Arr;
use Orchestra\Contracts\Auth\Guard;
use Orchestra\Model\User as Eloquent;
use Orchestra\Contracts\Auth\Command\AuthenticateUser as Command;
use Orchestra\Foundation\Validation\AuthenticateUser as Validator;
use Orchestra\Contracts\Auth\Listener\AuthenticateUser as Listener;

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
     *
     * @return mixed
     */
    public function login(Listener $listener, array $input)
    {
        $validation = $this->validator->on('login')->with($input);

        // Validate user login, if any errors is found redirect it back to
        // login page with the errors.
        if ($validation->fails()) {
            return $listener->userLoginHasFailedValidation($validation->getMessageBag());
        }

        if (! $this->authenticate($input)) {
            return $listener->userLoginHasFailedAuthentication($input);
        }

        $user = $this->getUser();

        $this->verifyWhenFirstTimeLogin($user);

        return $listener->userHasLoggedIn($user);
    }

    /**
     * Authenticate the user.
     *
     * @param  array  $input
     *
     * @return bool
     */
    protected function authenticate($input)
    {
        $data = Arr::only($input, ['email', 'password']);

        $remember = (isset($input['remember']) && $input['remember'] === 'yes');

        // We should now attempt to login the user using Auth class. If this
        // failed simply return false.
        if (! $this->auth->attempt($data, $remember)) {
            return false;
        }

        return true;
    }

    /**
     * Verify user account if has not been verified, other this should
     * be ignored in most cases.
     *
     * @param  \Orchestra\Model\User  $user
     *
     * @return void
     */
    protected function verifyWhenFirstTimeLogin(Eloquent $user)
    {
        if ((int) $user->getAttribute('status') === Eloquent::UNVERIFIED) {
            $user->activate()->save();
        }
    }
}
