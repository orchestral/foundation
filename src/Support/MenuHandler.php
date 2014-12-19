<?php namespace Orchestra\Foundation\Support;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Container\Container;

abstract class MenuHandler
{
    /**
     * The foundation implementation.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Menu configuration.
     *
     * @var array
     */
    protected $menu = [
        'id'       => null,
        'position' => '*',
        'title'    => null,
        'link'     => '#',
        'icon'     => null,
    ];

    /**
     * Menu instance.
     *
     * @var \Orchestra\Widget\Handlers\Menu
     */
    protected $handler;

    /**
     * Construct a new handler.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->handler = $container->make('orchestra.platform.menu');
    }

    /**
     * Create a handler for `orchestra.ready: admin` event.
     *
     * @return void
     */
    public function handle()
    {
        if (! $this->passesAuthorization()) {
            return ;
        }

        $menu = $this->createMenu()
                    ->title($this->title())
                    ->link($this->link());

        if (! is_null($icon = $this->icon())) {
            $menu->icon($icon);
        }
    }

    /**
     * Get the URL.
     *
     * @return string
     */
    public function getLink()
    {
        return handles($this->menu['link']);
    }

    /**
     * Create a new menu.
     *
     * @return \Illuminate\Support\Fluent
     */
    protected function createMenu()
    {
        return $this->handler->add($this->id(), $this->position());
    }

    /**
     * Determine if the request passes the authorization check.
     *
     * @return bool
     */
    protected function passesAuthorization()
    {
        if (method_exists($this, 'authorize')) {
            return $this->container->call([$this, 'authorize']);
        }

        return false;
    }

    /**
     *  Handle dynamic calls to the container to get attributes.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($name, $parameters)
    {
        $method = 'get'.ucfirst($name);

        if (method_exists($this, $method)) {
            return $this->container->call([$this, $method]);
        }

        return Arr::get($this->menu, $name);
    }
}
