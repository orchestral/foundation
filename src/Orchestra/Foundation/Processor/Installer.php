<?php namespace Orchestra\Foundation\Processor;

use ReflectionException;
use Illuminate\Support\Facades\Config;
use Orchestra\Foundation\Routing\BaseController;
use Orchestra\Foundation\Installation\InstallerInterface;
use Orchestra\Foundation\Installation\RequirementInterface;
use Orchestra\Support\Facades\App;

class Installer
{
    /**
     * Installer instance.
     *
     * @var \Orchestra\Foundation\Installation\Installer
     */
    protected $installer;

    /**
     * Requirement instance.
     *
     * @var \Orchestra\Foundation\Installation\Requirement
     */
    protected $requirement;

    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Foundation\Installation\Installer    $presenter
     * @param  \Orchestra\Foundation\Installation\Requirement  $validator
     */
    public function __construct(InstallerInterface $installer, RequirementInterface $requirement)
    {
        $this->installer   = $installer;
        $this->requirement = $requirement;
    }

    /**
     * Start an installation and check for requirement.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function index(BaseController $listener)
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

        return $listener->indexSucceed($data);
    }

    /**
     * Run migration and prepare the database.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function prepare(BaseController $listener)
    {
        $this->installer->migrate();

        return $listener->prepareSucceed();
    }

    /**
     * Display initial user and site configuration page.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function create(BaseController $listener)
    {
        return $listener->createSucceed(array(
            'siteName' => 'Orchestra Platform',
        ));
    }

    /**
     * Store/save administator information and site configuration
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @param  array                                           $input
     * @return mixed
     */
    public function store(BaseController $listener, array $input)
    {
        if (! $this->installer->createAdmin($input)) {
            return $listener->storeFailed();
        }

        return $listener->storeSucceed();
    }

    /**
     * Complete the installation.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function done(BaseController $listener)
    {
        return $listener->doneSucceed();
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
        if (isset($database['password']) and ($password = strlen($database['password']))) {
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
        try {
            $eloquent = App::make($auth['model']);

            return ($auth['driver'] === 'eloquent' and $eloquent instanceof User);
        } catch (ReflectionException $e) {
            return false;
        }
    }
}
