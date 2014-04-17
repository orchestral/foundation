<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Traits\ControllerResponseTrait;

abstract class BaseController extends Controller
{
    use ControllerResponseTrait;

    /**
     * Processor instance.
     *
     * @var object
     */
    protected $processor;

    /**
     * Base controller construct method.
     */
    public function __construct()
    {
        $this->setupFilters();
    }

    /**
     * Setup controller filters.
     *
     * @return void
     */
    abstract protected function setupFilters();

    /**
     * Show missing pages.
     *
     * GET (:orchestra) return 404
     *
     * @param  array    $parameters
     * @return Response
     */
    public function missingMethod($parameters = array())
    {
        return Response::view('orchestra/foundation::dashboard.missing', $parameters, 404);
    }
}
