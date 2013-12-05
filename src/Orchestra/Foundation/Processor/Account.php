<?php namespace Orchestra\Foundation\Processor;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Orchestra\Foundation\Routing\BaseController;
use Orchestra\Foundation\Presenter\Account as AccountPresenter;
use Orchestra\Foundation\Validation\Account as AccountValidator;

class Account extends AbstractableProcessor
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
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function showProfile(BaseController $listener)
    {
        $eloquent = Auth::user();
        $form = $this->presenter->profile($eloquent, handles('orchestra::account'));

        $this->fireEvent('form', array($eloquent, $form));

        return $listener->showProfileSucceed(compact('eloquent', 'form'));
    }

    /**
     * Update profile information.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @param  array                                           $input
     * @return mixed
     */
    public function updateProfile(BaseController $listener, array $input)
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
            return $listener->updateProfileFailed(
                trans('orchestra/foundation::response.db-failed', array(
                    'error' => $e->getMessage(),
                ))
            );
        }

        return $listener->updateProfileSucceed(
            trans('orchestra/foundation::response.account.profile.update')
        );
    }

    /**
     * Get password information.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function showPassword(BaseController $listener)
    {
        $eloquent = Auth::user();
        $form = $this->presenter->password($eloquent);

        return $listener->showPasswordSucceed(compact('eloquent', 'form'));
    }

    /**
     * Update password information.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @param  array                                           $input
     * @return mixed
     */
    public function updatePassword(BaseController $listener, array $input)
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
            return $listener->updatePasswordFailed(
                trans('orchestra/foundation::response.account.password.invalid')
            );
        }

        $user->password = $input['new_password'];

        try {
            DB::transaction(function () use ($user) {
                $user->save();
            });

            return $listener->updatePasswordSucceed(
                trans('orchestra/foundation::response.account.password.update')
            );
        } catch (Exception $e) {
            return $listener->updatePasswordFailed(
                trans('orchestra/foundation::response.db-failed')
            );
        }
    }

    /**
     * Fire Event related to eloquent process
     *
     * @param  string   $type
     * @param  array    $parameters
     * @return void
     */
    private function fireEvent($type, array $parameters = array())
    {
        Event::fire("orchestra.{$type}: user.account", $parameters);
    }
}
