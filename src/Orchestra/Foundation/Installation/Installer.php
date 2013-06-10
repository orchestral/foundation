<?php namespace Orchestra\Foundation\Installation;

use Exception;
use Orchestra\Model\User;

class Installer implements InstallerInterface {
	
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
		$this->app['orchestra.publisher.migrate']->foundation();
		$this->app['events']->fire('orchestra.install.schema');

		return true;
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
			! $multipleAdmin and $this->hasNoExistingUser();

			$this->runApplicationSetup($input);

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
	 * @param  array    $input
	 * @return void
	 */
	protected function runApplicationSetup($input)
	{
		// Bootstrap auth services, so we can use orchestra/auth package 
		// configuration.
		$user    = $this->createUser($input);
		$memory  = $this->app['orchestra.memory']->make();
		$actions = array('Manage Orchestra', 'Manage Users');
		$admin   = $this->app['config']->get('orchestra/foundation::roles.admin', 1);
		$roles   = $this->app['orchestra.role']->newQuery()->lists('name', 'id');
		$theme   = array(
			'frontend' => 'default',
			'backend'  => 'default',
		);

		// Attach Administrator role to the newly created administrator.
		$user->roles()->sync(array($admin));

		// Add some basic configuration for Orchestra Platform, including 
		// email configuration.
		$memory->put('site.name', $input['site_name']);
		$memory->put('site.theme', $theme);
		$memory->put('email', $this->app['config']->get('mail'));
		$memory->put('email.from', array(
			'name'    => $input['site_name'],
			'address' => $input['email']
		));

		// We should also create a basic ACL for Orchestra Platform, since 
		// the basic roles is create using Fluent Query Builder we need 
		// to manually insert the roles.
		$acl = $this->app['orchestra.acl']->make('orchestra');
		$acl->actions()->fill($actions);
		$acl->roles()->fill(array_values($roles));
		$acl->allow($roles[$admin], $actions);

		$this->app['events']->fire('orchestra.install: acl', array($acl));

		$acl->attach($memory);
	}

	/**
	 * Create user account.
	 *
	 * @access protected
	 * @param  array    $input
	 * @return Orchestra\Model\User
	 */
	protected function createUser($input)
	{
		User::unguard();
		$user = $this->app['orchestra.user']->newInstance();

		$user->fill(array(
			'email'    => $input['email'],
			'password' => $input['password'],
			'fullname' => $input['fullname'],
			'status'   => 0,
		));

		$this->app['events']->fire('orchestra.install: user', array($user, $input));

		$user->save();

		return $user;
	}

	/**
	 * Check for existing User.
	 *
	 * @access protected
	 * @return boolean
	 */
	protected function hasNoExistingUser()
	{
		$users = $this->app['orchestra.user']->newQuery()->all();

		// Before we create administrator, we should ensure that users table 
		// is empty to avoid any possible hijack or invalid request.
		if (empty($users)) return true;

		throw new Exception(trans('orchestra/foundation::install.user.duplicate'));
	}
}
