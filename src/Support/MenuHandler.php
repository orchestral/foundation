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
     * Create a handler.
     *
     * @return void
     */
    public function handle()
    {
        if (! $this->passesAuthorization()) {
            return ;
        }

        $menu = $this->createMenu();

        $this->attachIcon($menu);
    }

    /**
     *  Handle get attributes.
     *
     * @param  string  $name
     * @return mixed
     */
    public function getAttribute($name)
    {
        $methods = ['get'.ucfirst($name).'Attribute', 'get'.ucfirst($name)];
        $value = Arr::get($this->menu, $name);

        foreach ($methods as $method) {
            if (method_exists($this, $method)) {
                return $this->container->call([$this, $method], ['value' => $value]);
            }
        }

        return $value;
    }

    /**
     * Get the URL.
     *
     * @param  string  $value
     * @return string
     */
    public function getLinkAttribute($value)
    {
        return $this->container['orchestra.app']->handles($value);
    }

    /**
     * Create a new menu.
     *
     * @return \Illuminate\Support\Fluent|null
     */
    protected function createMenu()
    {
        return $this->handler->add($this->getAttribute('id'), $this->getAttribute('position'))
                    ->title($this->getAttribute('title'))
                    ->link($this->getAttribute('link'));
    }

    /**
     * Attach icon to menu.
     *
     * @param  \Illuminate\Support\Fluent|null  $menu
     * @return void
     */
    protected function attachIcon(Fluent $menu = null)
    {
        if (! is_null($menu) && ! is_null($icon = $this->getAttribute('icon'))) {
            $menu->icon($icon);
        }
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
        return $this->getAttribute($name);
    }
}
