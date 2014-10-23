<?php namespace Orchestra\Foundation\Routing;

use Orchestra\Support\Facades\Meta;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Processor\Resource as ResourceProcessor;

class ResourcesController extends AdminController
{
    /**
     * Orchestra Platform resources routing is dynamically handle by this
     * Controller.
     *
     * @param  \Orchestra\Foundation\Processor\Resource    $processor
     */
    public function __construct(ResourceProcessor $processor)
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
     * Add a drop-in resource anywhere on Orchestra
     *
     * @param  string $request
     * @param  array  $arguments
     * @return mixed
     */
    public function call($request)
    {
        if ($request === 'index') {
            return $this->index();
        }

        return $this->processor->call($this, $request);
    }

    /**
     * Response when index page succeed.
     *
     * @param  array  $data
     * @return mixed
     */
    public function indexSucceed(array $data)
    {
        Meta::set('title', trans('orchestra/foundation::title.resources.list'));
        Meta::set('description', trans('orchestra/foundation::title.resources.list-detail'));

        return View::make('orchestra/foundation::resources.index', $data);
    }

    /**
     * Response when call resource page succeed.
     *
     * @param  array  $data
     * @return mixed
     */
    public function callSucceed(array $data)
    {
        return View::make('orchestra/foundation::resources.page', $data);
    }
}
