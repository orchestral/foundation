<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Orchestra\Support\Facades\Messages;

abstract class BaseController extends Controller
{
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
     * @param  string   $method
     * @param  array    $parameters
     * @return Response
     */
    public function missingMethod($method, $parameters = array())
    {
        return Response::view('orchestra/foundation::dashboard.missing', $parameters, 404);
    }

    /**
     * Queue notification and redirect.
     *
     * @param  string  $to
     * @param  string  $message
     * @param  string  $type
     * @return Response
     */
    protected function redirectWithMessage($to, $message = null, $type = 'success')
    {
        ! is_null($message) and Messages::add($type, $message);

        return Redirect::to($to);
    }
}
