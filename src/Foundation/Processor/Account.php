<?php namespace Orchestra\Foundation\Processor;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Orchestra\Foundation\Presenter\Account as AccountPresenter;
use Orchestra\Foundation\Validation\Account as AccountValidator;

class Account extends Processor
{
    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Foundation\Presenter\Account  $presenter
     * @param  \Orchestra\Foundation\Validation\Account $validator
     */
    public function __construct(AccountPresenter $presenter, AccountValidator $validator)
    {
        $this->presenter = $presenter;
        $this->validator = $validator;
    }

    /**
     * Get account/profile information.
     *
     * @param  object  $listener
     * @return mixed
     */
    public function showProfile($listener)
    {
        $eloquent = Auth::user();
        $form = $this->presenter->profile($eloquent, 'orchestra::account');

        $this->fireEvent('form', array($eloquent, $form));

        return $listener->showProfileSucceed(array('eloquent' => $eloquent, 'form' => $form));
    }

    /**
     * Update profile information.
     *
     * @param  object  $listener
     * @param  array   $input
     * @return mixed
     */
    public function updateProfile($listener, array $input)
    {
        $user = Auth::user();

        if ((string) $user->id !== $input['id']) {
            return $listener->suspend(500);
        }

        $validation = $this->validator->with($input);

        if ($validation->fails()) {
            return $listener->updateProfileValidationFailed($validation);
        }

        $user->email    = $input['email'];
        $user->fullname = $input['fullname'];

        try {
            $this->fireEvent('updating', array($user));
            $this->fireEvent('saving', array($user));

            DB::transaction(function () use ($user) {
                $user->save();
            });

            $this->fireEvent('updated', array($user));
            $this->fireEvent('saved', array($user));

        } catch (Exception $e) {
            return $listener->updateProfileFailed(array('error' => $e->getMessage()));
        }

        return $listener->updateProfileSucceed();
    }

    /**
     * Get password information.
     *
     * @param  object  $listener
     * @return mixed
     */
    public function showPassword($listener)
    {
        $eloquent = Auth::user();
        $form = $this->presenter->password($eloquent);

        return $listener->showPasswordSucceed(array('eloquent' => $eloquent, 'form' => $form));
    }

    /**
     * Update password information.
     *
     * @param  object  $listener
     * @param  array   $input
     * @return mixed
     */
    public function updatePassword($listener, array $input)
    {
        $user = Auth::user();

        if ((string) $user->id !== $input['id']) {
            return $listener->suspend(500);
        }

        $validation = $this->validator->on('changePassword')->with($input);

        if ($validation->fails()) {
            return $listener->updatePasswordValidationFailed($validation);
        }

        if (! Hash::check($input['current_password'], $user->password)) {
            return $listener->verifyCurrentPasswordFailed();
        }

        $user->password = $input['new_password'];

        try {
            DB::transaction(function () use ($user) {
                $user->save();
            });
        } catch (Exception $e) {
            return $listener->updatePasswordFailed(array('error' => $e->getMessage()));
        }

        return $listener->updatePasswordSucceed();
    }

    /**
     * Fire Event related to eloquent process
     *
     * @param  string  $type
     * @param  array   $parameters
     * @return void
     */
    private function fireEvent($type, array $parameters = array())
    {
        Event::fire("orchestra.{$type}: user.account", $parameters);
    }
}
