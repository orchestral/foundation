<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Site;
use Orchestra\Model\User;

class CredentialController extends AdminController {

	/**
	 * Define the filters.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->beforeFilter('orchestra.guest', array(
			'only' => array(
				'getLogin', 'postLogin', 
				'getRegister', 'postRegister',
			),
		));

		$this->beforeFilter('orchestra.registrable', array(
			'only' => array(
				'getRegister', 'postRegister',
			),
		));

		$this->beforeFilter('orchestra.csrf', array('only' => array('postLogin')));
	}

	/**
	 * Login Page
	 *
	 * GET (:orchestra)/login
	 *
	 * @return Response
	 */
	public function getLogin()
	{
		Site::set('title', trans("orchestra/foundation::title.login"));

		return View::make('orchestra/foundation::credential.login');
	}

	/**
	 * POST Login
	 *
	 * POST (:orchestra)/login
	 *
	 * @return Response
	 */
	public function postLogin()
	{
		$input      = Input::all();
		$validation = App::make('Orchestra\Foundation\Services\Validation\Auth')
						->on('login')->with($input);

		// Validate user login, if any errors is found redirect it back to
		// login page with the errors.
		if ($validation->fails())
		{
			return Redirect::to(handles('orchestra::login'))
					->withInput()
					->withErrors($validation);
		}

		if ($this->authenticate($input))
		{
			Messages::add('success', trans('orchestra/foundation::response.credential.logged-in'));

			return Redirect::intended(handles('orchestra::/'));
		}

		Messages::add('error', trans('orchestra/foundation::response.credential.invalid-combination'));

		return Redirect::to(handles('orchestra::login'));
	}

	/**
	 * Logout the user
	 *
	 * DELETE (:bundle)/login
	 *
	 * @return Response
	 */
	public function deleteLogin()
	{
		Auth::logout();
		Messages::add('success', trans('orchestra/foundation::response.credential.logged-out'));
		
		return Redirect::intended(handles('orchestra::login'));
	}

	/**
	 * Authenticate the user.
	 *
	 * @param  array    $input
	 * @return boolean
	 */
	protected function authenticate($input)
	{
		$data = array(
			'email'    => $input['email'],
			'password' => $input['password'],
		);

		$remember = (isset($input['remember']) and $input['remember'] === 'yes');

		// We should now attempt to login the user using Auth class. If this 
		// failed simply return false.
		if ( ! Auth::attempt($data, $remember)) return false;
		
		$user = Auth::user();

		// Verify user account if has not been verified, other this should 
		// be ignored in most cases.
		if ((int) $user->status === User::UNVERIFIED)
		{
			$user->status = User::VERIFIED;
			$user->save();
		}

		return true;
	}
}
