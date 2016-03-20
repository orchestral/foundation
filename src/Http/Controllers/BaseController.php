<?php

namespace Orchestra\Foundation\Http\Controllers;

use Orchestra\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Orchestra\Routing\Traits\ControllerResponse;

abstract class BaseController extends Controller
{
    use ControllerResponse, DispatchesJobs;

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
        $this->setupMiddleware();
    }

    /**
     * Setup controller middleware.
     *
     * @return void
     */
    abstract protected function setupMiddleware();

    /**
     * Show missing pages.
     *
     * GET (:orchestra) return 404
     *
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function missingMethod($parameters = [])
    {
        return Response::view('orchestra/foundation::dashboard.missing', $parameters, 404);
    }
}
