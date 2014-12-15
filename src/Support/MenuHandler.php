<?php namespace Orchestra\Foundation\Support;

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
     * Menu instance.
     *
     * @var \Orchestra\Widget\Handlers\Menu
     */
    protected $menu;

    /**
     * Construct a new handler.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->menu = $container->make('orchestra.platform.menu');
    }

    /**
     * Create a handler for `orchestra.ready: admin` event.
     *
     * @return void
     */
    public function handles()
    {
        if (! $this->passesAuthorization()) {
            return ;
        }

        $menu = $this->createMenu()
                    ->title($this->getTitle())
                    ->link($this->getLink());

        if (! method_exist($this, 'getIcon')) {
            $menu->icon($this->getIcon());
        }
    }

    /**
     * Get ID.
     *
     * @return string
     */
    abstract protected function getId();

    /**
     * Get position.
     *
     * @return string
     */
    abstract protected function getPosition();

    /**
     * Get the title.
     *
     * @return string
     */
    abstract protected function getTitle();

    /**
     * Get the URL.
     *
     * @return string
     */
    abstract protected function getLink();

    /**
     * Create a new menu.
     *
     * @return \Illuminate\Support\Fluent
     */
    protected function createMenu()
    {
        return $this->menu->add($this->getId(), $this->getPosition());
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
}
