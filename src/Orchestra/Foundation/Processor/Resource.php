<?php namespace Orchestra\Foundation\Processor;

use Orchestra\Support\Facades\Resources;
use Orchestra\Foundation\Routing\BaseController;
use Orchestra\Foundation\Presenter\Resource as ResourcePresenter;

class Resource extends AbstractableProcessor
{
    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Foundation\Html\ResourcePresenter $presenter
     */
    public function __construct(ResourcePresenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * View list resources page.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function index(BaseController $listener)
    {
        $resources  = Resources::all();
        $collection = array();

        foreach ($resources as $name => $options) {
            if (false !== value($options->visible)) {
                $collection[$name] = $options;
            }
        }

        $table = $this->presenter->table($collection);

        return $listener->indexSucceed(compact('eloquent', 'table'));
    }

    /**
     * View call a resource page.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @param  string                                          $request
     * @return mixed
     */
    public function call(BaseController $listener, $request)
    {
        $resources  = Resources::all();
        $parameters = explode('/', trim($request, '/'));
        $name       = array_shift($parameters);
        $content    = Resources::call($name, $parameters);

        return Resources::response($content, function ($content) use ($resources, $name, $request, $listener) {
            ( ! str_contains($name, '.')) ?
                $namespace = $name : list($namespace,) = explode('.', $name, 2);

            return $listener->callSucceed(array(
                'content'   => $content,
                'resources' => array(
                    'list'      => $resources,
                    'namespace' => $namespace,
                    'name'      => $name,
                    'request'   => $request,
                ),
            ));
        });
    }
}
