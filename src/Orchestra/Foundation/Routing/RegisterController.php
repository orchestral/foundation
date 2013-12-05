<?php namespace Orchestra\Foundation\Routing;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Mail;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Site;
use Orchestra\Support\Str;
use Orchestra\Model\User;
use Orchestra\Foundation\Presenter\Account as AccountPresenter;
use Orchestra\Foundation\Validation\Account as AccountValidator;

class RegisterController extends AdminController
{
    /**
     * Registration Controller routing. It should only be accessible if
     * registration is allowed through the setting.
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
        $this->beforeFilter('orchestra.registrable');
        $this->beforeFilter('orchestra.csrf', array('only' => array('create')));
    }

    /**
     * User Registration Page.
     *
     * GET (:orchestra)/register
     *
     * @return Response
     */
    public function index()
    {
        $eloquent = App::make('orchestra.user');
        $title    = 'orchestra/foundation::title.register';
        $form     = $this->presenter->profileForm($eloquent, handles('orchestra::register'));

        $form->extend(function ($form) use ($title) {
            $form->submit = $title;
        });

        Event::fire('orchestra.form: user.account', array($eloquent, $form));

        Site::set('title', trans($title));

        return View::make('orchestra/foundation::credential.register', compact('eloquent', 'form'));
    }

    /**
     * Create a new user.
     *
     * POST (:orchestra)/register
     *
     * @return Response
     */
    public function create()
    {
        $input    = Input::all();
        $password = Str::random(5);

        $validation = $this->validator->on('register')->with($input);

        // Validate user registration, if any errors is found redirect it
        // back to registration page with the errors
        if ($validation->fails()) {
            return Redirect::to(handles('orchestra::register'))
                    ->withInput()
                    ->withErrors($validation);
        }

        $user = App::make('orchestra.user');

        $user->email    = $input['email'];
        $user->fullname = $input['fullname'];
        $user->password = $password;

        try {
            $this->fireEvent('creating', array($user));
            $this->fireEvent('saving', array($user));

            DB::transaction(function () use ($user) {
                $user->save();
                $user->roles()->sync(array(
                    Config::get('orchestra/foundation::roles.member', 2)
                ));
            });

            $this->fireEvent('created', array($user));
            $this->fireEvent('saved', array($user));

            Messages::add('success', trans("orchestra/foundation::response.users.create"));
        } catch (Exception $e) {
            Messages::add('error', trans('orchestra/foundation::response.db-failed', array(
                'error' => $e->getMessage(),
            )));

            return Redirect::to(handles('orchestra::register'))->withInput();
        }

        return $this->sendEmail($user, $password);
    }
    /**
     * Send new registration e-mail to user.
     *
     * @param  User     $user
     * @param  string   $password
     * @param  Messages $msg
     * @return Response
     */
    protected function sendEmail(User $user, $password)
    {
        // Converting the user to an object allow the data to be a generic
        // object. This allow the data to be transferred to JSON if the
        // mail is send using queue.

        $memory = App::memory();
        $site   = $memory->get('site.name', 'Orchestra Platform');
        $data   = array(
            'password' => $password,
            'site'     => $site,
            'user'     => (object) $user->toArray(),
        );

        $callback = function ($mail) use ($data, $user, $site) {
            $mail->subject(trans('orchestra/foundation::email.credential.register', array('site' => $site)));
            $mail->to($user->email, $user->fullname);
        };

        $sent = Mail::push('orchestra/foundation::email.credential.register', $data, $callback);

        if (count($sent) > 0 or true === $memory->get('email.queue', false)) {
            Messages::add('success', trans('orchestra/foundation::response.credential.register.email-send'));
        } else {
            Messages::add('error', trans('orchestra/foundation::response.credential.register.email-fail'));
        }

        return Redirect::intended(handles('orchestra::login'));
    }

    /**
     * Fire Event related to eloquent process
     *
     * @param  string   $type
     * @param  array    $parameters
     * @return void
     */
    protected function fireEvent($type, $parameters)
    {
        Event::fire("orchestra.{$type}: user.account", $parameters);
    }
}
