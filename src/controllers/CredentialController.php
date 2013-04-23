<?php namespace Orchestra\Foundation;

use Auth,
	Event,
	View,
	Orchestra\Messages,
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
	 * @return View
	 */
	public function getLogin()
	{
		Site::set('title', trans("orchestra/foundation::title.login"));

		return View::make('orchestra/foundation::credential.login')
			->with('redirect', Session::get('orchestra.redirect', handles('orchestra/foundation::/')));
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

		Messages::make()->add('success', trans('orchestra/foundation::response.credential.logged-out'));

		return Redirect::to(Input::get('redirect', handles('orchestra/foundation::login')));
	}
}