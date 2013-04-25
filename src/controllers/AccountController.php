<?php namespace Orchestra\Foundation;

use Auth,
	DB,
	Event,
	Input,
	Redirect,
	View,
	Orchestra\App,
	Orchestra\Messages,
	Orchestra\Site,
	Orchestra\Foundation\Services\Html\AccountPresenter;

class AccountController extends AdminController {

	/**
	 * Construct Account Controller to allow user to update own profile.
	 * Only authenticated user should be able to access this controller.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->beforeFilter('orchestra.auth');
	}

	/**
	 * Edit User Profile Page
	 *
	 * GET (:orchestra)/account
	 *
	 * @access public
	 * @return Response
	 */
	public function getIndex()
	{
		$eloquent = Auth::user();
		$form     = AccountPresenter::profileForm($eloquent, handles('orchestra/foundation::account/index'));

		Event::fire('orchestra.form: user.account', array($eloquent, $form));
		Site::set('title', trans("orchestra/foundation::title.account.profile"));

		return View::make('orchestra/foundation::account.index', compact('eloquent', 'form'));
	}

	/**
	 * POST Edit User Profile
	 *
	 * POST (:orchestra)/account
	 *
	 * @access public
	 * @return Response
	 */
	public function postIndex()
	{
		$input = Input::all();
		
		if (Auth::user()->id !== $input['id']) return App::abort(500);

		$validation = App::make('Orchestra\Services\Validation\UserAccount')
						->on('updateProfile')->with($input);

		if ($validation->fails())
		{
			return Redirect::to(handles('orchestra/foundation::account'))
					->withInput()
					->withErrors($validation);
		}

		$user = Auth::user();

		$user->email    = $input['email'];
		$user->fullname = $input['fullname'];

		try
		{
			$this->fireEvent('updating', array($user));
			$this->fireEvent('saving', array($user));

			DB::transaction(function () use ($user)
			{
				$user->save();
			});

			$this->fireEvent('updated', array($user));
			$this->fireEvent('saved', array($user));

			Messages::add('success', trans('orchestra/foundation::response.account.profile.update'));
		}
		catch (Exception $e)
		{
			Messages::add('error', trans('orchestra/foundation::response.db-failed', array(
				'error' => $e->getMessage(),
			)));
		}

		return Redirect::to(handles('orchestra/foundation::account'));
	}

	/**
	 * Fire Event related to eloquent process
	 *
	 * @access private
	 * @param  string   $type
	 * @param  array    $parameters
	 * @return void
	 */
	private function fireEvent($type, $parameters)
	{
		Event::fire("orchestra.{$type}: user.account", $parameters);
	}
}