<?php namespace Orchestra\Foundation\Http\Handlers;

use Orchestra\Foundation\Support\MenuHandler;

class ResourcesMenuHandler extends MenuHandler
{
    /**
     * Create a new menu.
     *
     * @return void
     */
    protected function createMenu()
    {
        $repository = $this->container->make('orchestra.resources')->all();

        // Resources menu should only be appended if there is actually
        // resources to be displayed.
        if (! empty($repository)) {
            $this->resources($repository);
        }
    }

    /**
     * Resources links.
     *
     * @param  array  $resources
     * @return void
     */
    protected function resources($resources)
    {
        $foundation = $this->container['orchestra.app'];
        $translator = $this->container['translator'];

        $boot = function ($foundation, $menu, $translator) {
            $menu->add('resources', '>:extensions')
                ->title($translator->trans('orchestra/foundation::title.resources.list'))
                ->link($foundation->handles('orchestra::resources'));
        };

        foreach ($resources as $name => $option) {
            if (false === value($option->visible)) {
                continue;
            }

            if (! is_null($boot)) {
                $boot($foundation, $this->handler, $translator);
                $boot = null;
            }

            $this->handler->add($name, '^:resources')
                ->title($option->name)
                ->link($foundation->handles("orchestra::resources/{$name}"));
        }
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
