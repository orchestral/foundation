<?php namespace Orchestra\Foundation\Http\Controllers;

use Orchestra\Foundation\Processor\ResourceLoader as Processor;
use Orchestra\Contracts\Foundation\Listener\ResourceLoader as Listener;

class ResourcesController extends AdminController implements Listener
{
    /**
     * Orchestra Platform resources routing is dynamically handle by this
     * Controller.
     *
     * @param  \Orchestra\Foundation\Processor\ResourceLoader  $processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;

        parent::__construct();
    }

    /**
     * Setup controller filters.
     *
     * @return void
     */
    protected function setupFilters()
    {
        //
    }

    /**
     * Route to Resources List.
     *
     * @return mixed
     */
    public function index()
    {
        return $this->processor->index($this);
    }

    /**
     * Add a drop-in resource anywhere on Orchestra Platform.
     *
     * @param  string  $request
     *
     * @return mixed
     */
    public function show($request)
    {
        if ($request === 'index') {
            return $this->index();
        }

        return $this->processor->show($this, $request);
    }

    /**
     * Response when show resources lists succeed.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function showResourcesList(array $data)
    {
        set_meta('title', trans('orchestra/foundation::title.resources.list'));
        set_meta('description', trans('orchestra/foundation::title.resources.list-detail'));

        return view('orchestra/foundation::resources.index', $data);
    }

    /**
     * Response when load resource succeed.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function onRequestSucceed(array $data)
    {
        return view('orchestra/foundation::resources.page', $data);
    }
}
