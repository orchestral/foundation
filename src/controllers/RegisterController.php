<?php namespace Orchestra\Routing;

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
use Orchestra\Services\Html\AccountPresenter;

class RegisterController extends AdminController {
	
	/**
	 * Define the filters.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// Registration controller should only be accessible if we allow 
		// registration through the setting.
		$this->beforeFilter('orchestra.registrable');
		$this->beforeFilter('orchestra.csrf', array('only' => array('postIndex')));
	}

	/**
	 * User Registration Page.
	 *
	 * GET (:orchestra)/register
	 *
	 * @access public
	 * @return Response
	 */
	public function getIndex()
	{
		$eloquent = App::make('orchestra.user');
		$title    = 'orchestra/foundation::title.register';
		$form     = AccountPresenter::profileForm($eloquent, handles('orchestra/foundation::register'));
		
		$form->extend(function ($form) use ($title)
		{
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
	 * @access public
	 * @return Response
	 */
	public function postIndex()
	{
		$input    = Input::all();
		$password = Str::random(5);
		
		$validation = App::make('\Orchestra\Services\Validation\UserAccount')
						->on('register')->with($input);
	
		// Validate user registration, if any errors is found redirect it 
		// back to registration page with the errors
		if ($validation->fails())
		{
			return Redirect::to(handles('orchestra/foundation::register'))
					->withInput()
					->withErrors($validation);
		}

		$user = App::make('orchestra.user');

		$user->email    = $input['email'];
		$user->fullname = $input['fullname'];
		$user->password = $password;

		try
		{
			$this->fireEvent('creating', array($user));
			$this->fireEvent('saving', array($user));

			DB::transaction(function () use ($user)
			{
				$user->save();
				$user->roles()->sync(array(
					Config::get('orchestra/auth::member', 2)
				));
			});

			$this->fireEvent('created', array($user));
			$this->fireEvent('saved', array($user));

			Messages::add('success', trans("orchestra/foundation::response.users.create"));
		}
		catch (Exception $e)
		{
			Messages::add('error', trans('orchestra/foundation::response.db-failed', array(
				'error' => $e->getMessage(),
			)));
			
			return Redirect::to(handles('orchestra/foundation::register'));
		}

		return $this->sendEmail($user, $password);
	}
	/**
	 * Send new registration e-mail to user.
	 *
	 * @access protected
	 * @param  User     $user
	 * @param  string   $password
	 * @param  Messages $msg
	 * @return Response
	 */
	protected function sendEmail(User $user, $password)
	{
		$site = App::memory()->get('site.name', 'Orchestra Platform');
		$data = compact('password', 'site', 'user');

		$sent = Mail::send('orchestra/foundation::email.credential.register', $data, function ($m) 
			use ($data, $user, $site)
		{
			$m->subject(trans('orchestra/foundation::email.credential.register', compact('site')));
			$m->to($user->email, $user->fullname);
		});

		if (count($sent) < 1)
		{
			Messages::add('error', trans('orchestra/foundation::response.credential.register.email-fail'));
		}
		else
		{
			Messages::add('success', trans('orchestra/foundation::response.credential.register.email-send'));
		}

		return Redirect::intended(handles('orchestra/foundation::login'));
	}

	/**
	 * Fire Event related to eloquent process
	 *
	 * @access protected
	 * @param  string   $type
	 * @param  array    $parameters
	 * @return void
	 */
	protected function fireEvent($type, $parameters)
	{
		Event::fire("orchestra.{$type}: user.account", $parameters);
	}
}
