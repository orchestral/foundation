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
	 * @return void
	 */
	public function __construct(InstallerInterface $installer, RequirementInterface $requirement)
	{
		$this->installer   = $installer;
		$this->requirement = $requirement;

		Site::set('navigation::usernav', false);
		Site::set('title', 'Installer');

		parent::__construct();
	}

	/**
	 * Setup controller filters.
	 *
	 * @return void
	 */
	protected function setupFilters()
	{
		$this->beforeFilter('orchestra.installed', array(
			'only' => array('getIndex', 'getCreate', 'postCreate'),
		));
	}
	
	/**
	 * Check installation requirement page.
	 *
	 * GET (:orchestra)/install
	 *
	 * @return View
	 */
	public function getIndex()
	{
		$requirement = $this->requirement;
		$installable = $requirement->check();

		list($database, $auth, $authentication) = $this->getRunningConfiguration();

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
	 * Migrate database schema for Orchestra Platform.
	 *
	 * GET (:orchestra)/install/prepare
	 *
	 * @return Redirect
	 */
	public function getPrepare()
	{
		$this->installer->migrate();

		return Redirect::to(handles('orchestra::install/create'));
	}
	
	/**
	 * Show create adminstrator page.
	 *
	 * GET (:orchestra)/install/create
	 *
	 * @return View
	 */
	public function getCreate()
	{
		return View::make('orchestra/foundation::install.create')
			->with('siteName', 'Orchestra Platform');
	}

	/**
	 * Create an adminstrator.
	 *
	 * POST (:orchestra)/install/create
	 *
	 * @return View
	 */
	public function postCreate()
	{
		if ( ! $this->installer->createAdmin(Input::all()))
		{
			return Redirect::to(handles('orchestra::install/create'));
		}

		return Redirect::to(handles('orchestra::install/done'));
	}

	/**
	 * End of installation.
	 *
	 * GET (:orchestra)/install/done
	 *
	 * @return View
	 */
	public function getDone()
	{
		return View::make('orchestra/foundation::install.done');
	}

	/**
	 * Get running configuration.
	 *
	 * @return array
	 */
	protected function getRunningConfiguration()
	{
		$driver         = Config::get('database.default', 'mysql');
		$database       = Config::get("database.connections.{$driver}", array());
		$auth           = Config::get('auth');
		$authentication = false;

		// For security, we shouldn't expose database connection to anyone, 
		// This snippet change the password value into *.
		if (isset($database['password'])
			and ($password = strlen($database['password'])))
		{
			$database['password'] = str_repeat('*', $password);
		}

		$authentication = $this->isAuthenticationInstallable($auth);

		return array($database, $auth, $authentication);
	}

	/**
	 * Is authentication installable.
	 * 
	 * @param  array    $auth
	 * @return boolean
	 */
	protected function isAuthenticationInstallable($auth)
	{
		// Orchestra Platform strictly require Eloquent based authentication 
		// because our Role Based Access Role (RBAC) is utilizing on eloquent
		// relationship to solve some of the requirement.
		if ( ! ($auth['driver'] === 'eloquent' and class_exists($auth['model']))) return false;

		$eloquent = App::make($auth['model']);
		
		if ( ! (isset($eloquent) and $eloquent instanceof User)) return false;

		return true;
	}
}
