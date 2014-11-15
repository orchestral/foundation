<?php namespace Orchestra\Foundation\Processor;

use Orchestra\Support\Facades\Resources;
use Orchestra\Foundation\Presenter\Resource as ResourcePresenter;

class Resource extends Processor
{
    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Foundation\Presenter\Resource  $presenter
     */
    public function __construct(ResourcePresenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * View list resources page.
     *
     * @param  object  $listener
     * @return mixed
     */
    public function index($listener)
    {
        $resources = Resources::all();
        $eloquent  = [];

        foreach ($resources as $name => $options) {
            if (false !== value($options->visible)) {
                $eloquent[$name] = $options;
            }
        }

        $table = $this->presenter->table($eloquent);

        return $listener->indexSucceed(['eloquent' => $eloquent, 'table' => $table]);
    }

    /**
     * View call a resource page.
     *
     * @param  object  $listener
     * @param  string  $request
     * @return mixed
     */
    public function call($listener, $request)
    {
        $resources  = Resources::all();
        $parameters = explode('/', trim($request, '/'));
        $name       = array_shift($parameters);
        $content    = Resources::call($name, $parameters);

        return Resources::response($content, function ($content) use ($resources, $name, $request, $listener) {
            ( ! str_contains($name, '.')) ?
                $namespace = $name : list($namespace,) = explode('.', $name, 2);

            return $listener->callSucceed([
                'content'   => $content,
                'resources' => [
                    'list'      => $resources,
                    'namespace' => $namespace,
                    'name'      => $name,
                    'request'   => $request,
                ],
            ]);
        });
    }
}
