<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Routing\Controllers\Controller;
use Illuminate\Support\Facades\Response;

abstract class BaseController extends Controller
{
    /**
     * Presenter instance.
     *
     * @var object
     */
    protected $presenter;

    /**
     * Validator instance.
     *
     * @var object
     */
    protected $validator;

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
    public function missingMethod($parameters)
    {
        return Response::view('orchestra/foundation::dashboard.missing', $parameters, 404);
    }
}
