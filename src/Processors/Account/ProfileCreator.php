<?php

namespace Orchestra\Foundation\Processors\Account;

use Exception;
use Orchestra\Contracts\Foundation\Command\Account\ProfileCreator as Command;
use Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator as Listener;
use Orchestra\Foundation\Tools\GenerateRandomPassword;
use Orchestra\Model\User as Eloquent;
use Orchestra\Support\Facades\Foundation;

class ProfileCreator extends User implements Command
{
    /**
     * View registration page.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator  $listener
     *
     * @return mixed
     */
    public function create(Listener $listener)
    {
        $eloquent = Foundation::make('orchestra.user');
        $form = $this->presenter->profile($eloquent, 'orchestra::register');

        $form->extend(static function ($form) {
            $form->submit = 'orchestra/foundation::title.register';
        });

        $this->fireEvent('form', [$eloquent, $form]);

        return $listener->showProfileCreator(compact('eloquent', 'form'));
    }

    /**
     * Create a new user.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator  $listener
     * @param  array  $input
     *
     * @return mixed
     */
    public function store(Listener $listener, array $input)
    {
        $temporaryPassword = null;
        $password = $input['password'] ?? null;

        if (empty($password)) {
            $password = $temporaryPassword = \resolve(GenerateRandomPassword::class)();
        }

        $validation = $this->validator->on('register')->with($input);

        // Validate user registration, if any errors is found redirect it
        // back to registration page with the errors
        if ($validation->fails()) {
            return $listener->createProfileFailedValidation($validation->getMessageBag());
        }

        $user = Foundation::make('orchestra.user');

        try {
            $this->saving($user, $input, $password);
        } catch (Exception $e) {
            return $listener->createProfileFailed(['error' => $e->getMessage()]);
        }

        return $this->notifyCreatedUser($listener, $user, $temporaryPassword);
    }

    /**
     * Send new registration e-mail to user.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator  $listener
     * @param  \Orchestra\Model\User  $user
     * @param  string|null  $temporaryPassword
     *
     * @return mixed
     */
    protected function notifyCreatedUser(Listener $listener, Eloquent $user, ?string $temporaryPassword)
    {
        try {
            $user->sendWelcomeNotification($temporaryPassword);
        } catch (Exception $e) {
            return $listener->profileCreatedWithoutNotification();
        }

        return $listener->profileCreated();
    }

    /**
     * Saving new user.
     *
     * @param  \Orchestra\Model\User  $user
     * @param  array  $input
     * @param  string  $password
     *
     * @return void
     */
    protected function saving(Eloquent $user, array $input, $password)
    {
        $user->setAttribute('email', $input['email']);
        $user->setAttribute('fullname', $input['fullname']);
        $user->setAttribute('password', $password);

        $this->fireEvent('creating', [$user]);
        $this->fireEvent('saving', [$user]);

        $user->saveOrFail();

        $this->fireEvent('created', [$user]);
        $this->fireEvent('saved', [$user]);
    }
}
