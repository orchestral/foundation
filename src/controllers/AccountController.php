<?php namespace Orchestra\Routing;

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
use Orchestra\Services\Html\AccountPresenter;

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
		$form     = AccountPresenter::profileForm($eloquent, handles('orchestra/foundation::account'));

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
		$user  = Auth::user();
		
		if ($user->id !== $input['id']) return App::abort(500);

		$validation = App::make('Orchestra\Services\Validation\UserAccount')
						->with($input);

		if ($validation->fails())
		{
			return Redirect::to(handles('orchestra/foundation::account'))
					->withInput()
					->withErrors($validation);
		}

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
	 * Edit Password Page
	 *
	 * GET (:orchestra)/account/password
	 *
	 * @access public
	 * @return Response
	 */
	public function getPassword()
	{
		$eloquent = Auth::user();
		$form     = AccountPresenter::passwordForm($eloquent);

		Site::set('title', trans("orchestra/foundation::title.account.password"));

		return View::make('orchestra/foundation::account.password', compact('eloquent', 'form'));
	}

	/**
	 * POST Edit User Password
	 *
	 * POST (:orchestra)/account/password
	 *
	 * @access public
	 * @return Response
	 */
	public function postPassword()
	{
		$input = Input::all();
		$user  = Auth::user();
		
		if ($user->id !== $input['id']) return App::abort(500);

		$validation = App::make('Orchestra\Services\Validation\UserAccount')
						->on('changePassword')->with($input);

		if ($validation->fails())
		{
			return Redirect::to(handles('orchestra/foundation::account/password'))
					->withInput()
					->withErrors($validation);
		}

		if (Hash::check($input['current_password'], $user->password))
		{
			$user->password = $input['new_password'];

			try
			{
				DB::transaction(function () use ($user)
				{
					$user->save();
				});

				Messages::add('success', trans('orchestra/foundation::response.account.password.update'));
			}
			catch (Exception $e)
			{
				Messages::add('error', trans('orchestra/foundation::response.db-failed'));
			}
		}
		else
		{
			Messages::add('error', trans('orchestra/foundation::response.account.password.invalid'));
		}

		return Redirect::to(handles('orchestra/foundation::account/password'));
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
