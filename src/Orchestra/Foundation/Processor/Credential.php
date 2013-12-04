<?php namespace Orchestra\Foundation\Processor;

use Illuminate\Support\Facades\Auth;
use Orchestra\Foundation\Routing\BaseController;
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
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @param  array                                           $input
     * @return mixed
     */
    public function login(BaseController $listener, array $input)
    {
        $validation = $this->validator->on('login')->with($input);

        // Validate user login, if any errors is found redirect it back to
        // login page with the errors.
        if ($validation->fails()) {
            return $listener->loginValidationFailed($validation);
        }

        if (! $this->authenticate($input)) {
            return $listener->loginFailed(
                trans('orchestra/foundation::response.credential.invalid-combination')
            );
        }

        return $listener->loginSucceed(
            trans('orchestra/foundation::response.credential.logged-in')
        );
    }

    /**
     * Logout a user.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function logout(BaseController $listener)
    {
        Auth::logout();

        return $listener->logoutSucceed(
            trans('orchestra/foundation::response.credential.logged-out')
        );
    }

    /**
     * Authenticate the user.
     *
     * @param  array    $input
     * @return boolean
     */
    protected function authenticate($input)
    {
        $data = array(
            'email'    => $input['email'],
            'password' => $input['password'],
        );

        $remember = (isset($input['remember']) and $input['remember'] === 'yes');

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
