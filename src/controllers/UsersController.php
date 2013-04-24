<?php namespace Orchestra\Foundation;

use Input,
	Event,
	View,
	Orchestra\App,
	Orchestra\Site,
	Orchestra\Model\Role,
	Orchestra\Model\User,
	Orchestra\Foundation\Services\UserPresenter;

class UsersController extends AdminController {

	/**
	 * Use restful verb.
	 * 
	 * @var boolean
	 */
	protected $restful = false;

	/**
	 * Define the filters.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// User has to be authenticated before using this controller.
		$this->beforeFilter('orchestra.auth');
		$this->beforeFilter('orchestra.manage:users');
	}

	/**
	 * List all the users.
	 *
	 * GET (:orchestra)/users
	 * 
	 * @access public
	 * @return Response
	 */
	public function index()
	{
		$keyword = Input::get('q', '');
		$roles   = Input::get('roles', array());

		// Get Users (with roles) and limit it to only 30 results for
		// pagination. Don't you just love it when pagination simply works.
		//$users = User::search($keyword, $roles)->paginate(30);
		$users = User::paginate(30);

		// Build users table HTML using a schema liked code structure.
		$table = UserPresenter::table($users);

		Event::fire('orchestra.list: users', array($users, $table));

		// Once all event listening to `orchestra.list: users` is executed,
		// we can add we can now add the final column, edit and delete action
		// for users
		UserPresenter::actions($table);

		$data = array(
			'eloquent' => $users,
			'table'    => $table,
			'roles'    => Role::lists('name', 'id'),
		);

		Site::set('title', trans('orchestra/foundation::title.users.list'));

		return View::make('orchestra/foundation::users.index', $data);
	}

	/**
	 * Create a new user.
	 *
	 * GET (:orchestra)/users/create
	 *
	 * @access public
	 * @return Response
	 */
	public function create()
	{
		$user = new User;
		$form = UserPresenter::form($user, 'create');
		$this->fireEvent('form', array($user, $form));

		$data = array(
			'eloquent' => $user,
			'form'     => $form,
		);

		Site::set('title', trans('orchestra/foundation::title.users.create'));

		return View::make('orchestra/foundation::users.edit', $data);
	}

	/**
	 * Edit the user.
	 *
	 * GET (:orchestra)/users/$id/edit
	 *
	 * @access public
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::find($id);

		if (is_null($user)) App::abort(404);

		$form = UserPresenter::form($user, 'update');
		$this->fireEvent('form', array($user, $form));

		$data = array(
			'eloquent' => $user,
			'form'     => $form,
		);

		Site::set('title', trans('orchestra/foundation::title.users.update'));

		return View::make('orchestra/foundation::users.edit', $data);
	}

	/**
	 * Request to delete a user.
	 *
	 * GET (:orchestra)/delete/$id
	 * 
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function delete($id)
	{
		return $this->destroy($id);
	}

	public function destroy($id)
	{
		
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
		Event::fire("orchestra.{$type}: users", $parameters);
		Event::fire("orchestra.{$type}: user.account", $parameters);
	}
}