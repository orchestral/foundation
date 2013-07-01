<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Site;
use Orchestra\Foundation\Installation\InstallerInterface;
use Orchestra\Foundation\Installation\RequirementInterface;
use Orchestra\Model\User;

class InstallController extends BaseController {

	/**
	 * Installer instance.
	 *
	 * @var Orchestra\Foundation\Installation\Installer
	 */
	protected $installer = null;

	/**
	 * Requirement instance.
	 *
	 * @var Orchestra\Foundation\Installation\Requirement
	 */
	protected $requirement = null;

	/**
	 * Construct InstallController
	 *
	 * @access public
	 * @return void
	 */
	public function __construct(InstallerInterface $installer, RequirementInterface $requirement)
	{
		$this->beforeFilter('orchestra.installed', array(
			'only' => array('getIndex', 'getCreate', 'postCreate'),
		));

		Site::set('navigation::usernav', false);
		Site::set('title', 'Installer');

		$this->installer   = $installer;
		$this->requirement = $requirement;
	}
	
	/**
	 * Check installation requirement page.
	 *
	 * GET (:orchestra)/install
	 *
	 * @access public
	 * @return View
	 */
	public function getIndex()
	{
		$requirement    = $this->requirement;
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
			if (class_exists($auth['model'])) $eloquent = App::make($auth['model']);
			
			if (isset($eloquent) 
				and $eloquent instanceof \Orchestra\Model\User) $authentication = true;
		}

		// If the auth status is false, installation shouldn't be possible.
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
	 * Migrate database schema for Orchestra Platform and show create 
	 * adminstrator page.
	 *
	 * GET (:orchestra)/install/create
	 *
	 * @access public
	 * @return View
	 */
	public function getCreate()
	{
		$this->installer->migrate();

		return View::make('orchestra/foundation::install.create')
			->with('siteName', 'Orchestra Platform');
	}

	/**
	 * Create an adminstrator.
	 *
	 * POST (:orchestra)/install/create
	 *
	 * @access public
	 * @return View
	 */
	public function postCreate()
	{
		if ( ! $this->installer->createAdmin(Input::all()))
		{
			return Redirect::to(handles('orchestra/foundation::install/create'));
		}

		return Redirect::to(handles('orchestra/foundation::install/done'));
	}

	/**
	 * End of installation.
	 *
	 * GET (:orchestra)/install/done
	 *
	 * @access public
	 * @return View
	 */
	public function getDone()
	{
		return View::make('orchestra/foundation::install.done');
	}
}
