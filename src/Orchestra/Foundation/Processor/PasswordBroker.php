<?php namespace Orchestra\Foundation\Processor;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Orchestra\Foundation\Routing\BaseController;
use Orchestra\Foundation\Validation\Auth as AuthValidator;
use Orchestra\Support\Facades\App;

class PasswordBroker extends AbstractableProcessor
{
    /**
     * Create a new processor instance.
     *
     * @param \Orchestra\Foundation\Validation\Auth $validator
     */
    public function __construct(AuthValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Request to reset password.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function request(BaseController $listener, array $input)
    {
        $validation = $this->validator->with($input);

        if ($validation->fails()) {
            return $listener->requestValidationFailed($validation);
        }

        $memory = App::memory();
        $site = $memory->get('site.name', 'Orchestra Platform');

        $response = Password::remind(array('email' => $input['email']), function ($mail) use ($site) {
            $mail->subject(trans('orchestra/foundation::email.forgot.request', array('site' => $site)));
        });

        if ($response !== Password::REMINDER_SENT) {
            return $listener->requestFailed(trans($response));
        }

        return $listener->requestSucceed(trans($response));
    }

    /**
     * Reset the password.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function reset(BaseController $listener, array $input)
    {
        $response = Password::reset($input, function ($user, $password) {
            // Save the new password and login the user.
            $user->password = $password;
            $user->save();

            Auth::login($user);
        });

        $errors = array(
            Password::INVALID_PASSWORD,
            Password::INVALID_TOKEN,
            Password::INVALID_USER,
        );

        if (in_array($response, $errors)) {
            return $listener->resetFailed(trans($response));
        }

        return $listener->resetSucceed(
            trans('orchestra/foundation::response.account.password.update')
        );
    }
}
