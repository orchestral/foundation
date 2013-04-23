<?php namespace Orchestra\Foundation;

use Config,
	View,
	Orchestra\App,
	Orchestra\Site,
	Orchestra\Model\User;

class InstallController extends BaseController {

	/**
	 * Construct InstallController
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		Site::set('navigation::usernav', false);
		Site::set('title', 'Installer');
		App::memory()->put('site.name', 'Orchestra Platform');
	}
	
	/**
	 * Check installation requirement page.
	 *
	 * GET (:orchestra)/install
	 *
	 * @access public
	 * @return View
	 */
	public function anyIndex()
	{
		$requirement    = new Installation\Requirement(App::getFacadeApplication());
		$driver         = Config::get('database.default', 'mysql');
		$database       = Config::get("database.connections.{$driver}", array());
		$auth           = Config::get('auth');
		$installable    = $requirement->check();
		$authentication = false;

		// For security, we shouldn't expose database connection to anyone, 
		// This snippet change the password value into *.
		if (isset($database['password'])
			and ($password = strlen($database['password'])))
		{
			$database['password'] = str_repeat('*', $password);
		}

		// Orchestra Platform strictly require Eloquent based authentication 
		// because our Role Based Access Role (RBAC) is utilizing on eloquent
		// relationship to solve some of the requirement.
		if ($auth['driver'] === 'eloquent')
		{
			if (class_exists($auth['model'])) $eloquent = with(new $auth['model']);
			
			if (isset($eloquent) 
				and $eloquent instanceof \Orchestra\Model\User) $authentication = true;
		}

		(true === $authentication) or $installable = false;

		$data = array(
			'database'       => $database,
			'auth'           => $auth,
			'authentication' => $authentication,
			'installable'    => $installable,
			'checklist'      => $requirement->getChecklist(),
		);
		
		return View::make('orchestra/foundation::install.index', $data);
	}

	/**
	 * Create adminstrator page.
	 *
	 * GET (:orchestra)/install/create
	 *
	 * @access public
	 * @return View
	 */
	public function getCreate()
	{
		// Migrate database schema for Orchestra Platform.
		App::illuminate()->make('orchestra.publisher.migrate')->foundation();

		return View::make('orchestra/foundation::install.create')
			->with('siteName', 'Orchestra Platform');
	}
}