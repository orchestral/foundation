<?php namespace Orchestra\Foundation\Processor\Account;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Orchestra\Foundation\Contracts\Command\Account\PasswordUpdater as Command;
use Orchestra\Foundation\Contracts\Listener\Account\PasswordUpdater as Listener;

class PasswordUpdater extends User implements Command
{
    /**
     * Get password information.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\Account\PasswordUpdater  $listener
     * @return mixed
     */
    public function show(Listener $listener)
    {
        $eloquent = Auth::user();
        $form = $this->presenter->password($eloquent);

        return $listener->showPasswordChanger(['eloquent' => $eloquent, 'form' => $form]);
    }

    /**
     * Update password information.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\Account\PasswordUpdater  $listener
     * @param  array  $input
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

        $user->setAttribute('password', $input['new_password']);

        try {
            DB::transaction(function () use ($user) {
                $user->save();
            });
        } catch (Exception $e) {
            return $listener->updatePasswordFailed(['error' => $e->getMessage()]);
        }

        return $listener->passwordUpdated();
    }
}
