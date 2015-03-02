<?php namespace Orchestra\Foundation\Http\Handlers;

use Orchestra\Foundation\Support\MenuHandler;

class ResourcesMenuHandler extends MenuHandler
{
    /**
     * Menu configuration.
     *
     * @var array
     */
    protected $menu = [
        'id'       => 'resources',
        'position' => '>:extensions',
        'title'    => 'orchestra/foundation::title.resources.list',
        'link'     => 'orchestra::resources',
        'icon'     => null,
    ];

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

        $repository = $this->container->make('orchestra.resources')->all();

        // Resources menu should only be appended if there is actually
        // resources to be displayed.
        if (! empty($repository)) {
            $menu = $this->resources($repository);

            $this->attachIcon($menu);
        }
    }

    /**
     * Resources links.
     *
     * @param  array  $resources
     *
     * @return \Illuminate\Support\Fluent|null
     */
    protected function resources($resources)
    {
        $menu       = null;
        $foundation = $this->container['orchestra.app'];
        $translator = $this->container['translator'];

        $boot = function () {
            return $this->createMenu();
        };

        foreach ($resources as $name => $option) {
            if (false === value($option->visible)) {
                continue;
            }

            if (! is_null($boot)) {
                $menu = $boot();
                $boot = null;
            }

            $this->handler->add($name, '^:resources')
                ->title($option->name)
                ->link($foundation->handles("orchestra::resources/{$name}"));
        }

        return $menu;
    }

    /**
     * Get the title.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function getTitleAttribute($value)
    {
        return $this->container['translator']->trans($value);
    }

    /**
     * Check authorization to display the menu.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->container->bound('orchestra.resources');
    }
}
