<?php namespace Orchestra\Foundation\Routing;

use Orchestra\Support\Facades\Meta;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Orchestra\Foundation\Processor\Installer as InstallerProcessor;

class InstallerController extends BaseController
{
    /**
     * Construct Installer controller.
     *
     * @param  \Orchestra\Foundation\Processor\Installer    $processor
     */
    public function __construct(InstallerProcessor $processor)
    {
        $this->processor = $processor;

        Meta::set('navigation::usernav', false);
        Meta::set('title', 'Installer');

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
            'only' => array('index', 'create', 'store'),
        ));
    }

    /**
     * Check installation requirement page.
     *
     * GET (:orchestra)/install
     *
     * @return View
     */
    public function index()
    {
        return $this->processor->index($this);
    }

    /**
     * Migrate database schema for Orchestra Platform.
     *
     * GET (:orchestra)/install/prepare
     *
     * @return Redirect
     */
    public function prepare()
    {
        return $this->processor->prepare($this);
    }

    /**
     * Show create adminstrator page.
     *
     * GET (:orchestra)/install/create
     *
     * @return View
     */
    public function create()
    {
        return $this->processor->create($this);
    }

    /**
     * Create an adminstrator.
     *
     * POST (:orchestra)/install/create
     *
     * @return View
     */
    public function store()
    {
        return $this->processor->store($this, Input::all());
    }

    /**
     * End of installation.
     *
     * GET (:orchestra)/install/done
     *
     * @return View
     */
    public function done()
    {
        return $this->processor->done($this);
    }

    /**
     * Response for installation welcome page.
     *
     * @param  array   $data
     * @return Response
     */
    public function indexSucceed(array $data)
    {
        return view('orchestra/foundation::install.index', $data);
    }


    /**
     * Response when installation is prepared.
     *
     * @return Response
     */
    public function prepareSucceed()
    {
        return $this->redirect(handles('orchestra::install/create'));
    }


    /**
     * Response view to input user information for installation.
     *
     * @param  array   $data
     * @return Response
     */
    public function createSucceed(array $data)
    {
        return view('orchestra/foundation::install.create', $data);
    }

    /**
     * Response when store installation config is failed.
     *
     * @return Response
     */
    public function storeFailed()
    {
        return $this->redirect(handles('orchestra::install/create'));
    }

    /**
     * Response when store installation config is succeed.
     *
     * @return Response
     */
    public function storeSucceed()
    {
        return $this->redirect(handles('orchestra::install/done'));
    }

    /**
     * Response when installation is done.
     *
     * @return Response
     */
    public function doneSucceed()
    {
        return view('orchestra/foundation::install.done');
    }
}
