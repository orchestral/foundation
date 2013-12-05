<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
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
    public function redirectWithMessage($to, $message = null, $type = 'success')
    {
        ! is_null($message) and Messages::add($type, $message);

        return $this->redirect($to);
    }

    /**
     * Queue notification and redirect.
     *
     * @param  string  $to
     * @param  mixed   $errors
     * @return Response
     */
    public function redirectWithErrors($to, $errors)
    {
        return $this->redirect($to)->withInput()->withErrors($errors);
    }

    /**
     * Redirect.
     *
     * @param  string  $to
     * @param  string  $message
     * @param  string  $type
     * @return Response
     */
    public function redirect($to)
    {
        return Redirect::to($to);
    }

    /**
     * Halt current request using App::abort().
     *
     * @param  integer $status
     * @return Response
     */
    public function suspend($status)
    {
        return App::abort($status);
    }
}
