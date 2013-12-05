<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Processor\Installer as InstallerProcessor;
use Orchestra\Support\Facades\Site;

class InstallerController extends BaseController
{
    /**
     * Construct Installer controller.
     */
    public function __construct(InstallerProcessor $processor)
    {
        $this->processor = $processor;

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
        return $this->processor->index($this);
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
        return $this->processor->prepare($this);
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
        return $this->processor->create($this);
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
        return $this->processor->store($this, Input::all());
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
        return $this->processor->done($this);
    }

    public function indexSucceed(array $data)
    {
        return View::make('orchestra/foundation::install.index', $data);
    }

    public function prepareSucceed()
    {
        return Redirect::to(handles('orchestra::install/create'));
    }

    public function createSucceed(array $data)
    {
        return View::make('orchestra/foundation::install.create', $data);
    }

    public function storeFailed()
    {
        return Redirect::to(handles('orchestra::install/create'));
    }

    public function storeSucceed()
    {
        return Redirect::to(handles('orchestra::install/done'));
    }

    public function doneSucceed()
    {
        return View::make('orchestra/foundation::install.done');
    }
}
