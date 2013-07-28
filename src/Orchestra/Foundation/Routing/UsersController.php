<?php namespace Orchestra\Foundation\Routing;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Site;
use Orchestra\Model\Role;
use Orchestra\Model\User;
use Orchestra\Foundation\Services\Html\UserPresenter;

class UsersController extends AdminController {

	/**
	 * Define the filters.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->beforeFilter('orchestra.auth');
		$this->beforeFilter('orchestra.manage:users');
	}

	/**
	 * List all the users.
	 *
	 * GET (:orchestra)/users
	 * 
	 * @return Response
	 */
	public function index()
	{
		$searchKeyword = Input::get('q', '');
		$searchRoles   = Input::get('roles', array());

		// Get Users (with roles) and limit it to only 30 results for
		// pagination. Don't you just love it when pagination simply works.
		$eloquent = App::make('orchestra.user')->search($searchKeyword, $searchRoles)->paginate(30);
		$roles    = App::make('orchestra.role')->lists('name', 'id');

		// Build users table HTML using a schema liked code structure.
		$table = UserPresenter::table($eloquent);

		Event::fire('orchestra.list: users', array($eloquent, $table));

		// Once all event listening to `orchestra.list: users` is executed,
		// we can add we can now add the final column, edit and delete 
		// action for users.
		UserPresenter::actions($table);

		Site::set('title', trans('orchestra/foundation::title.users.list'));

		return View::make('orchestra/foundation::users.index', compact(
			'eloquent', 'table', 'roles', 'searchKeyword', 'searchRoles'
		));
	}

	/**
	 * Create a new user.
	 *
	 * GET (:orchestra)/users/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$eloquent = App::make('orchestra.user');
		$form     = UserPresenter::form($eloquent, 'create');

		$this->fireEvent('form', array($eloquent, $form));
		Site::set('title', trans('orchestra/foundation::title.users.create'));

		return View::make('orchestra/foundation::users.edit', compact('eloquent', 'form'));
	}

	/**
	 * Edit the user.
	 *
	 * GET (:orchestra)/users/$id/edit
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		$eloquent = App::make('orchestra.user')->findOrFail($id);
		$form     = UserPresenter::form($eloquent, 'update');

		$this->fireEvent('form', array($eloquent, $form));
		Site::set('title', trans('orchestra/foundation::title.users.update'));

		return View::make('orchestra/foundation::users.edit', compact('eloquent', 'form'));
	}

	/**
	 * Create the user.
	 *
	 * POST (:orchestra)/users
	 *
	 * @return Response
	 */
	public function store() 
	{
		$input      = Input::all();
		$validation = App::make('Orchestra\Foundation\Services\Validation\User')
						->on('create')->with($input);

		if ($validation->fails())
		{
			return Redirect::to(handles("orchestra::users/create"))
					->withInput()
					->withErrors($validation);
		}

		$user           = App::make('orchestra.user');
		$user->status   = User::UNVERIFIED;
		$user->password = $input['password'];

		$this->saving($user, $input, 'create');

		return Redirect::to(handles('orchestra::users'));
	}

	/**
	 * Update the user.
	 *
	 * PUT (:orchestra)/users/1
	 *
	 * @param  integer  $id
	 * @return Response
	 */
	public function update($id) 
	{
		$input = Input::all();

		// Check if provided id is the same as hidden id, just a pre-caution.
		if ((int) $id !== (int) $input['id']) return App::abort(500);

		$validation = App::make('Orchestra\Foundation\Services\Validation\User')
						->on('update')->with($input);

		if ($validation->fails())
		{
			return Redirect::to(handles("orchestra::users/{$id}/edit"))
					->withInput()
					->withErrors($validation);
		}

		$user = App::make('orchestra.user')->findOrFail($id);
		
		if ( ! empty($input['password'])) $user->password = $input['password'];

		$this->saving($user, $input, 'update');

		return Redirect::to(handles('orchestra::users'));
	}

	/**
	 * Save the user.
	 *
	 * @param  Orchestra\Model\User $user
	 * @param  array                $input
	 * @param  string               $type
	 * @return boolean
	 */
	protected function saving(User $user, $input = array(), $type = 'create')
	{
		$beforeEvent = ($type === 'create' ? 'creating' : 'updating');
		$afterEvent  = ($type === 'create' ? 'created' : 'updated');

		$user->fullname = $input['fullname'];
		$user->email    = $input['email'];

		try
		{
			$this->fireEvent($beforeEvent, array($user));
			$this->fireEvent('saving', array($user));

			DB::transaction(function () use ($user, $input)
			{
				$user->save();
				$user->roles()->sync($input['roles']);
			});

			$this->fireEvent($afterEvent, array($user));
			$this->fireEvent('saved', array($user));

			Messages::add('success', trans("orchestra/foundation::response.users.{$type}"));
		}
		catch (Exception $e)
		{
			Messages::add('error', trans('orchestra/foundation::response.db-failed', array(
				'error' => $e->getMessage(),
			)));
			return false;
		}

		return true;
	}

	/**
	 * Request to delete a user.
	 *
	 * GET (:orchestra)/$id/delete
	 * 
	 * @param  integer  $id 
	 * @return Response
	 */
	public function delete($id)
	{
		return $this->destroy($id);
	}

	/**
	 * Request to delete a user.
	 *
	 * DELETE (:orchestra)/$id
	 * 
	 * @param  integer  $id 
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = App::make('orchestra.user')->findOrFail($id);

		// Avoid self-deleting accident.
		if ($user->id === Auth::user()->id) return App::abort(404);
		
		try
		{
			$this->fireEvent('deleting', array($user));

			DB::transaction(function () use ($user)
			{
				$user->delete();
			});

			$this->fireEvent('deleted', array($user));

			Messages::add('success', trans('orchestra/foundation::response.users.delete'));
		}
		catch (Exception $e)
		{
			Messages::add('error', trans('orchestra/foundation::response.db-failed', array(
				'error' => $e->getMessage(),
			)));
		}

		return Redirect::to(handles('orchestra::users'));
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
		Event::fire("orchestra.{$type}: users", $parameters);
		Event::fire("orchestra.{$type}: user.account", $parameters);
	}
}
