<?php

namespace Orchestra\Foundation\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Fluent;
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
        'with'     => [],
    ];

    /**
     * Menu's id.
     *
     * @var string
     */
    protected $id;

    /**
     * List of items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Hide the menu.
     *
     * @var bool|null
     */
    protected $enabled;

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
        $this->handler   = $container->make('orchestra.platform.menu');
    }

    /**
     * Create a handler.
     *
     * @return void
     */
    public function handle()
    {
        $this->prepare();

        if ($this->enabled) {
            $this->create();
        }
    }

    /**
     * Create a new menu.
     *
     * @return $this
     */
    public function create()
    {
        $menu = $this->handler->add($this->id, $this->getAttribute('position'))
                    ->title($this->getAttribute('title'))
                    ->link($this->getAttribute('link'))
                    ->handles(Arr::get($this->menu, 'link'));

        $this->attachIcon($menu);
        $this->handleNestedMenu();

        return $this;
    }

    /**
     * Determine if the request passes the authorization check.
     *
     * @return bool
     */
    public function passes()
    {
        return $this->enabled;
    }

    /**
     * Handle get attributes.
     *
     * @param  string  $name
     *
     * @return mixed
     */
    public function getAttribute($name)
    {
        $method = 'get'.ucfirst($name).'Attribute';
        $value  = isset($this->menu[$name]) ? $this->menu[$name] : null;

        if (method_exists($this, $method)) {
            return $this->container->call([$this, $method], ['value' => $value]);
        }

        return $value;
    }

    /**
     * Set attribute.
     *
     * @param  string  $name
     * @param  mixed   $value
     *
     * @return $this
     */
    public function setAttribute($name, $value)
    {
        if (in_array($name, ['id'])) {
            $this->{$name} = $value;
        }

        $this->menu[$name] = $value;

        return $this;
    }

    /**
     * Get the URL.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function getLinkAttribute($value)
    {
        return $this->container->make('orchestra.app')->handles($value);
    }

    /**
     * Attach icon to menu.
     *
     * @param  \Illuminate\Support\Fluent|null  $menu
     *
     * @return void
     */
    protected function attachIcon(Fluent $menu = null)
    {
        if (! (is_null($menu) || is_null($icon = $this->getAttribute('icon')))) {
            $menu->icon($icon);
        }
    }

    /**
     * Prepare nested menu.
     *
     * @return $this
     */
    public function prepare()
    {
        $id       = $this->getAttribute('id');
        $menus    = isset($this->menu['with']) ? $this->menu['with'] : [];
        $parent   = $this->getAttribute('position');
        $position = Str::startsWith($parent, '^:') ? $parent.'.' : '^:';

        foreach ((array) $menus as $class) {
            $menu = $this->container->make($class)
                            ->setAttribute('position', "{$position}{$id}")
                            ->prepare();

            if ($menu->passes()) {
                $this->items[] = $menu;
            }
        }

        $this->id = $id;
        $this->enabled = $this->passesAuthorization();

        return $this;
    }

    /**
     * Attach nested menu.
     *
     * @return $this
     */
    protected function handleNestedMenu()
    {
        foreach ((array) $this->items as $menu) {
            $menu->create();
        }

        return $this;
    }

    /**
     * Determine if the request passes the authorization check.
     *
     * @return bool
     */
    protected function passesAuthorization()
    {
        $enabled = false;

        if (method_exists($this, 'authorize')) {
            $enabled = $this->container->call([$this, 'authorize']);
        }

        if (! is_bool($enabled)) {
            $enabled = ! empty($this->items);
        }

        return $enabled;
    }

    /**
     *  Handle dynamic calls to the container to get attributes.
     *
     * @param  string  $name
     * @param  array   $parameters
     *
     * @return mixed
     */
    public function __call($name, $parameters)
    {
        return $this->getAttribute($name);
    }
}
