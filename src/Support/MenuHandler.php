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
     * Construct a new handler.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
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

        $menu = $this->container->make('orchestra.platform.menu')
                    ->add($this->getId(), $this->getPosition())
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
    protected abstract function getId();

    /**
     * Get position.
     *
     * @return string
     */
    protected abstract function getPosition();

    /**
     * Get the icon.
     *
     * @return string
     */
    protected abstract function getIcon();

    /**
     * Get the title.
     *
     * @return string
     */
    protected abstract function getTitle();

    /**
     * Get the URL.
     *
     * @return string
     */
    protected abstract function getLink();

    /**
     * Determine if the request passes the authorization check.
     *
     * @return bool
     */
    protected function passesAuthorization()
    {
        if (method_exists($this, 'authorize'))
        {
            return $this->container->call([$this, 'authorize']);
        }

        return false;
    }
}
