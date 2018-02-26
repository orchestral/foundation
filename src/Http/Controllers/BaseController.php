<?php

namespace Orchestra\Foundation\Http\Controllers;

use Orchestra\Routing\Controller;
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
        $this->onCreate();
    }

    /**
     * Setup controller middleware.
     *
     * @return void
     */
    abstract protected function onCreate();
}
