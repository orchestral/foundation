<?php namespace Orchestra\Foundation\Processor;

use Orchestra\Resources\Factory;
use Orchestra\Support\Facades\Resources;
use Orchestra\Foundation\Presenter\Resource as Presenter;
use Orchestra\Foundation\Contracts\Command\ResourceLoader as Command;
use Orchestra\Foundation\Contracts\Listener\ResourceLoader as Listener;

class ResourceLoader extends Processor implements Command
{
    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Foundation\Presenter\Resource  $presenter
     * @param  \Orchestra\Resources\Factory  $resources
     */
    public function __construct(Presenter $presenter, Factory $resources)
    {
        $this->presenter = $presenter;
        $this->resources = $resources;
    }

    /**
     * View list resources page.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\ResourceLoader  $listener
     * @return mixed
     */
    public function showAll(Listener $listener)
    {
        $resources = $this->resources->all();
        $eloquent  = [];

        foreach ($resources as $name => $options) {
            if (false !== value($options->visible)) {
                $eloquent[$name] = $options;
            }
        }

        $table = $this->presenter->table($eloquent);

        return $listener->showResourcesList(['eloquent' => $eloquent, 'table' => $table]);
    }

    /**
     * View call a resource page.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\ResourceLoader  $listener
     * @param  string  $request
     * @return mixed
     */
    public function request(Listener $listener, $request)
    {
        $resources  = $this->resources->all();
        $parameters = explode('/', trim($request, '/'));
        $name       = array_shift($parameters);

        return $this->resources->response(
            $this->resources->call($name, $parameters),
            $this->getResponseCallback($listener, $request, $resources, $name)
        );
    }

    /**
     * Get response callback.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\ResourceLoader  $listener
     * @param  string  $request
     * @param  array  $resources
     * @param  string  $name
     * @return callable
     */
    protected function getResponseCallback(Listener $listener, $request, array $resources, $name)
    {
        return function ($content) use ($resources, $name, $request, $listener) {
            ( ! str_contains($name, '.')) ?
                $namespace = $name : list($namespace,) = explode('.', $name, 2);

            return $listener->onRequestSucceed([
                'content'   => $content,
                'resources' => [
                    'list'      => $resources,
                    'namespace' => $namespace,
                    'name'      => $name,
                    'request'   => $request,
                ],
            ]);
        };
    }
}
