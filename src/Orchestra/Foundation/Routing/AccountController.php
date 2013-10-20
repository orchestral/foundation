<?php namespace Orchestra\Foundation\Routing;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Site;
use Orchestra\Foundation\Presenter\Account as AccountPresenter;
use Orchestra\Foundation\Validation\Account as AccountValidator;

class AccountController extends AdminController
{
    /**
     * Construct Account Controller to allow user to update own profile.
     * Only authenticated user should be able to access this controller.
     *
     * @param  \Orchestra\Foundation\Presenter\Account  $presenter
     * @param  \Orchestra\Foundation\Validation\Account $validator
     */
    public function __construct(AccountPresenter $presenter, AccountValidator $validator)
    {
        $this->presenter = $presenter;
        $this->validator = $validator;

        parent::__construct();
    }

    /**
     * Setup controller filters.
     *
     * @return void
     */
    protected function setupFilters()
    {
        $this->beforeFilter('orchestra.auth');
    }

    /**
     * Edit User Profile Page
     *
     * GET (:orchestra)/account
     *
     * @return Response
     */
    public function getIndex()
    {
        $eloquent  = Auth::user();
        $form      = $this->presenter->profileForm($eloquent, handles('orchestra::account'));

        Event::fire('orchestra.form: user.account', array($eloquent, $form));
        Site::set('title', trans("orchestra/foundation::title.account.profile"));

        return View::make('orchestra/foundation::account.index', array(
            'eloquent' => $eloquent,
            'form'     => $form,
        ));
    }

    /**
     * POST Edit User Profile
     *
     * POST (:orchestra)/account
     *
     * @return Response
     */
    public function postIndex()
    {
        $input = Input::all();
        $user  = Auth::user();

        if ((string) $user->id !== $input['id']) {
            return App::abort(500);
        }

        $validation = $this->validator->with($input);

        if ($validation->fails()) {
            return Redirect::to(handles('orchestra::account'))
                    ->withInput()
                    ->withErrors($validation);
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

            Messages::add('success', trans('orchestra/foundation::response.account.profile.update'));
        } catch (Exception $e) {
            Messages::add('error', trans('orchestra/foundation::response.db-failed', array(
                'error' => $e->getMessage(),
            )));
        }

        return Redirect::to(handles('orchestra::account'));
    }

    /**
     * Edit Password Page
     *
     * GET (:orchestra)/account/password
     *
     * @return Response
     */
    public function getPassword()
    {
        $eloquent  = Auth::user();
        $form      = $this->presenter->passwordForm($eloquent);

        Site::set('title', trans("orchestra/foundation::title.account.password"));

        return View::make('orchestra/foundation::account.password', array(
            'eloquent' => $eloquent,
            'form'     => $form,
        ));
    }

    /**
     * POST Edit User Password
     *
     * POST (:orchestra)/account/password
     *
     * @return Response
     */
    public function postPassword()
    {
        $input = Input::all();
        $user  = Auth::user();

        if ((string) $user->id !== $input['id']) {
            return App::abort(500);
        }

        $validation = $this->validator->on('changePassword')->with($input);

        if ($validation->fails()) {
            return Redirect::to(handles('orchestra::account/password'))
                    ->withInput()
                    ->withErrors($validation);
        }

        if (Hash::check($input['current_password'], $user->password)) {
            $user->password = $input['new_password'];

            $this->updatePassword($user);
        } else {
            Messages::add('error', trans('orchestra/foundation::response.account.password.invalid'));
        }

        return Redirect::to(handles('orchestra::account/password'));
    }

    /**
     * Update password for the user.
     *
     * @param  \Orchestra\Model\User    $user
     * @return void
     */
    protected function updatePassword($user)
    {
        try {
            DB::transaction(function () use ($user) {
                $user->save();
            });

            Messages::add('success', trans('orchestra/foundation::response.account.password.update'));
        } catch (Exception $e) {
            Messages::add('error', trans('orchestra/foundation::response.db-failed'));
        }
    }

    /**
     * Fire Event related to eloquent process
     *
     * @param  string   $type
     * @param  array    $parameters
     * @return void
     */
    private function fireEvent($type, $parameters)
    {
        Event::fire("orchestra.{$type}: user.account", $parameters);
    }
}
