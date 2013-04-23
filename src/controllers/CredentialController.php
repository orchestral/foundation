<?php namespace Orchestra\Foundation;

use Auth,
	Event,
	Input,
	Redirect,
	Session,
	View,
	Orchestra\Messages,
	Orchestra\Model\User,
	Orchestra\Site;

class CredentialController extends AdminController {

	/**
	 * Define the filters.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->beforeFilter('orchestra.logged', array(
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

		$this->beforeFilter('orchestra.csrf', array(
			'only' => array(
				'postLogin', 'postRegister',
			),
		));
	}

	/**
	 * Login Page
	 *
	 * GET (:orchestra)/login
	 *
	 * @access public
	 * @return Response
	 */
	public function getLogin()
	{
		Site::set('title', trans("orchestra/foundation::title.login"));

		return View::make('orchestra/foundation::credential.login')
			->with('redirect', Session::get('orchestra.redirect', handles('orchestra/foundation::/')));
	}

	/**
	 * POST Login
	 *
	 * POST (:orchestra)/login
	 *
	 * @access public
	 * @return Response
	 */
	public function post_login()
	{
		$input      = Input::all();
		$validation = new \Orchestra\Services\Validation\Auth;
		$validation->make($input, $rules);

		// Validate user login, if any errors is found redirect it back to
		// login page with the errors.
		if ($validation->fails())
		{
			return Redirect::to(handles('orchestra/foundation::login'))
					->withInput()
					->withErrors($validation->get());
		}

		if ($this->authenticate($input))
		{
			Event::fire('orchestra.auth: login');

			Messages::add('success', __('orchestra/foundation::response.credential.logged-in'));

			return Redirect::to(Input::get('redirect', handles('orchestra/foundation::/')));
		}

		Messages::add('error', __('orchestra/foundation::response.credential.invalid-combination'));

		return Redirect::to(handles('orchestra/foundation::login'));
	}

	/**
	 * Logout the user
	 *
	 * DELETE (:bundle)/login
	 *
	 * @access public
	 * @return Response
	 */
	public function deleteLogin()
	{
		Event::fire('orchestra.auth: logout');

		Auth::logout();

		Messages::add('success', trans('orchestra/foundation::response.credential.logged-out'));

		return Redirect::to(Input::get('redirect', handles('orchestra/foundation::login')));
	}

	/**
	 * Authenticate the user.
	 *
	 * @access protected
	 * @param  array    $input
	 * @return boolean
	 */
	protected function authenticate($input)
	{
		$attempt = array(
			'username' => $input['username'],
			'password' => $input['password'],
			'remember' => (isset($input['remember']) and $input['remember'] === 'yes'),
		);

		// We should now attempt to login the user using Auth class.
		if (Auth::attempt($attempt))
		{
			$user = Auth::user();

			// Verify the user account if has not been verified.
			if ((int) $user->status === User::UNVERIFIED)
			{
				$user->status = User::VERIFIED;
				$user->save();
			}

			return true;
		}

		return false;
	}
}