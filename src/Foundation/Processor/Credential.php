<?php namespace Orchestra\Foundation\Processor;

use Illuminate\Support\Facades\Auth;
use Orchestra\Foundation\Validation\Auth as AuthValidator;
use Orchestra\Model\User;

class Credential extends AbstractableProcessor
{
    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Foundation\Validation\Account $validator
     */
    public function __construct(AuthValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Login a user.
     *
     * @param  object  $listener
     * @param  array   $input
     * @return mixed
     */
    public function login($listener, array $input)
    {
        $validation = $this->validator->on('login')->with($input);

        // Validate user login, if any errors is found redirect it back to
        // login page with the errors.
        if ($validation->fails()) {
            return $listener->loginValidationFailed($validation);
        }

        if (! $this->authenticate($input)) {
            return $listener->loginFailed();
        }

        return $listener->loginSucceed();
    }

    /**
     * Logout a user.
     *
     * @param  object  $listener
     * @return mixed
     */
    public function logout($listener)
    {
        Auth::logout();

        return $listener->logoutSucceed();
    }

    /**
     * Authenticate the user.
     *
     * @param  array    $input
     * @return bool
     */
    protected function authenticate($input)
    {
        $data = array(
            'email'    => $input['email'],
            'password' => $input['password'],
        );

        $remember = (isset($input['remember']) && $input['remember'] === 'yes');

        // We should now attempt to login the user using Auth class. If this
        // failed simply return false.
        if (! Auth::attempt($data, $remember)) {
            return false;
        }

        $user = Auth::user();

        // Verify user account if has not been verified, other this should
        // be ignored in most cases.
        if ((int) $user->status === User::UNVERIFIED) {
            $user->status = User::VERIFIED;
            $user->save();
        }

        return true;
    }
}
