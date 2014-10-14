<?php namespace Orchestra\Foundation\Processor;

use Orchestra\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\PasswordBroker as Password;
use Orchestra\Foundation\Validation\Auth as AuthValidator;

class PasswordBroker extends AbstractableProcessor
{
    /**
     * The password broker implementation.
     *
     * @var \Illuminate\Contracts\Auth\PasswordBroker
     */
    protected $password;

    /**
     * Create a new processor instance.
     *
     * @param \Orchestra\Foundation\Validation\Auth     $validator
     * @param \Illuminate\Contracts\Auth\PasswordBroker $password
     */
    public function __construct(AuthValidator $validator, Password $password)
    {
        $this->validator = $validator;
        $this->password = $password;
    }

    /**
     * Request to reset password.
     *
     * @param  object  $listener
     * @param  array   $input
     * @return mixed
     */
    public function create($listener, array $input)
    {
        $validation = $this->validator->with($input);

        if ($validation->fails()) {
            return $listener->requestValidationFailed($validation);
        }

        $memory = App::memory();
        $site  = $memory->get('site.name', 'Orchestra Platform');
        $data  = ['email' => $input['email']];

        $response = $this->password->sendResetLink($data, function ($mail) use ($site) {
            $mail->subject(trans('orchestra/foundation::email.forgot.request', ['site' => $site]));
        });

        if ($response !== Password::RESET_LINK_SENT) {
            return $listener->createFailed($response);
        }

        return $listener->createSucceed($response);
    }

    /**
     * Reset the password.
     *
     * @param  object  $listener
     * @param  array   $input
     * @return mixed
     */
    public function reset($listener, array $input)
    {
        $response = $this->password->reset($input, function ($user, $password) {
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
            return $listener->resetFailed($response);
        }

        return $listener->resetSucceed($response);
    }
}
