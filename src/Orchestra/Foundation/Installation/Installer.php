<?php namespace Orchestra\Foundation\Installation;

use Exception,
	Event,
	Validator,
	Orchestra\Model\User,
	Orchestra\Model\Role;

class Installer {
	
	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * Construct a new instance.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
		$file = rtrim($this->app['path'], '/').'/orchestra/installer.php';

		if ($this->app['files']->exists($file)) 
		{
			$this->app['files']->requireOnce($file);
		}
	}

	/**
	 * Migrate Orchestra Platform schema.
	 *
	 * @access public
	 * @return void
	 */
	public function migrate()
	{
		$this->app->make('orchestra.publisher.migrate')->foundation();
	}

	/**
	 * Create adminstrator account.
	 *
	 * @access public	
	 * @param  array    $input
	 * @return void
	 */
	public function createAdmin($input, $multipleAdmin = true) 
	{
		// Grab input fields and define the rules for user validations.
		$rules = array(
			'email'     => array('required', 'email'),
			'password'  => array('required'),
			'fullname'  => array('required'),
			'site_name' => array('required'),
		);

		$validation = $this->app['validator']->make($input, $rules);

		// Validate user registration, we should stop this process if
		// the user not properly formatted.
		if ($validation->fails())
		{
			$this->app['session']->flash('errors', $validation->messages());
			return false;
		}

		try
		{
			! $multipleAdmin and $this->checkExistingUser();

			// Create administator user
			$user           = new User;
			$user->email    = $input['email'];
			$user->password = $input['password'];
			$user->fullname = $input['fullname'];
			$user->status   = 0;

			$this->app['events']->fire('orchestra.install: user', array($user, $input));

			$user->save();

			$this->runApplicationSetup($user);

			// Installation is successful, we should be able to generate
			// success message to notify the user. Installer route will be
			// disabled after this point.
			$this->app['orchestra.messages']->add('success', trans('orchestra/foundation::install.user.created'));

			return true;
		}
		catch (Exception $e)
		{
			$this->app['orchestra.messages']->add('error', $e->getMessage());

			return false;
		}
	}

	/**
	 * Run application setup
	 *
	 * @access protected
	 * @return void
	 */
	protected function runApplicationSetup(User $user)
	{
		$actions = array('Manage Orchestra', 'Manage Users');

		// Attach Administrator role to the newly created administrator
		// account.
		$user->roles()->insert(array('name' => 'Administrator'));

		$memory = $this->app['orchestra.memory']->make();

		// Save the default application site_name.
		$memory->put('site.name', $input['site_name']);
		$memory->put('site.theme.backend', 'default');
		$memory->put('site.theme.frontend', 'default');
		$memory->put('email', $this->app['config']->get('mail'));
		$memory->put('email.from', $input['email']);

		Role::create(array('name' => 'Member'));

		// We should also create a basic ACL for Orchestra.
		$acl = $this->app['orchestra.acl']->make('orchestra');
		$acl->actions()->fill($actions);
		$acl->roles()->fill(array('Member', 'Administrator'));
		$acl->allow('Administrator', $actions);

		$this->app['events']->fire('orchestra.install: acl', array($acl));

		$acl->attach($memory);
	}

	/**
	 * Check for existing User.
	 *
	 * @access protected
	 * @return boolean
	 */
	protected function checkExistingUser()
	{
		$users = User::all();

		// Before we create administrator, we should ensure that users table 
		// is empty to avoid any possible hijack or invalid request.
		if ( ! empty($users))
		{
			throw new Exception(trans('orchestra/foundation::install.user.duplicate'));
		}
	}
}