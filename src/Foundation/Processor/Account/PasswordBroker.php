<?php namespace Orchestra\Foundation\Processor\Account;

use Illuminate\Support\Facades\Auth;
use Orchestra\Model\User as Eloquent;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Foundation\Processor\Processor;
use Illuminate\Contracts\Auth\PasswordBroker as Password;
use Orchestra\Foundation\Validation\AuthenticateUser as Validator;
use Orchestra\Foundation\Contracts\Listener\Account\PasswordReset;
use Orchestra\Foundation\Contracts\Listener\Account\PasswordResetLink;
use Orchestra\Foundation\Contracts\Command\Account\PasswordBroker as Command;

class PasswordBroker extends Processor implements Command
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
     * @param \Orchestra\Foundation\Validation\AuthenticateUser  $validator
     * @param \Illuminate\Contracts\Auth\PasswordBroker  $password
     */
    public function __construct(Validator $validator, Password $password)
    {
        $this->validator = $validator;
        $this->password = $password;
    }

    /**
     * Request to reset password.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\Account\PasswordResetLink  $listener
     * @param  array  $input
     * @return mixed
     */
    public function store(PasswordResetLink $listener, array $input)
    {
        $validation = $this->validator->with($input);

        if ($validation->fails()) {
            return $listener->resetLinkFailedValidation($validation->getMessageBag());
        }

        $memory = Foundation::memory();
        $site  = $memory->get('site.name', 'Orchestra Platform');
        $data  = ['email' => $input['email']];

        $response = $this->password->sendResetLink($data, function ($mail) use ($site) {
            $mail->subject(trans('orchestra/foundation::email.forgot.request', ['site' => $site]));
        });

        if ($response != Password::RESET_LINK_SENT) {
            return $listener->resetLinkFailed($response);
        }

        return $listener->resetLinkSent($response);
    }

    /**
     * Reset the password.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\Account\PasswordReset  $listener
     * @param  array  $input
     * @return mixed
     */
    public function update(PasswordReset $listener, array $input)
    {
        $response = $this->password->reset($input, function (Eloquent $user, $password) {
            // Save the new password and login the user.
            $user->setAttribute('password', $password);
            $user->save();

            Auth::login($user);
        });

        $errors = [
            Password::INVALID_PASSWORD,
            Password::INVALID_TOKEN,
            Password::INVALID_USER,
        ];

        if (in_array($response, $errors)) {
            return $listener->passwordResetHasFailed($response);
        }

        return $listener->passwordHasReset($response);
    }
}
