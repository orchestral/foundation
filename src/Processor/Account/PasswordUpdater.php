<?php

namespace Orchestra\Foundation\Processor\Account;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Orchestra\Model\User as Eloquent;
use Orchestra\Contracts\Foundation\Command\Account\PasswordUpdater as Command;
use Orchestra\Contracts\Foundation\Listener\Account\PasswordUpdater as Listener;

class PasswordUpdater extends User implements Command
{
    /**
     * Get password information.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\PasswordUpdater  $listener
     *
     * @return mixed
     */
    public function edit(Listener $listener)
    {
        $eloquent = Auth::user();
        $form = $this->presenter->password($eloquent);

        return $listener->showPasswordChanger(['eloquent' => $eloquent, 'form' => $form]);
    }

    /**
     * Update password information.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\PasswordUpdater  $listener
     * @param  array  $input
     *
     * @return mixed
     */
    public function update(Listener $listener, array $input)
    {
        $user = Auth::user();

        if (! $this->validateCurrentUser($user, $input)) {
            return $listener->abortWhenUserMismatched();
        }

        $validation = $this->validator->on('changePassword')->with($input);

        if ($validation->fails()) {
            return $listener->updatePasswordFailedValidation($validation->getMessageBag());
        }

        if (! Hash::check($input['current_password'], $user->password)) {
            return $listener->verifyCurrentPasswordFailed();
        }

        try {
            $this->saving($user, $input);
        } catch (Exception $e) {
            return $listener->updatePasswordFailed(['error' => $e->getMessage()]);
        }

        return $listener->passwordUpdated();
    }

    /**
     * Saving new password.
     *
     * @param  \Orchestra\Model\User $user
     * @param  array  $input
     */
    protected function saving(Eloquent $user, array $input)
    {
        $user->setAttribute('password', $input['new_password']);

        $user->saveOrFail();
    }
}
