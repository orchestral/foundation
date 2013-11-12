<?php namespace Orchestra\Foundation\Services;

use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Resources;

class AdminMenuHandler
{
    /**
     * ACL instance.
     *
     * @var \Orchestra\Auth\Acl\Container
     */
    protected $acl;

    /**
     * Menu instance.
     *
     * @var \Orchestra\Widget\Drivers\Menu
     */
    protected $menu;

    /**
     * Translator instance.
     *
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * Construct a new handler.
     *
     * @return void
     */
    public function __construct()
    {
        $this->menu       = App::menu();
        $this->acl        = App::acl();
        $this->translator = App::make('translator');
    }

    /**
     * Create a handler for `orchestra.ready: admin` event.
     *
     * @return void
     */
    public function handle()
    {
        $this->settings();

        $resources = Resources::all();

        // Resources menu should only be appended if there is actually
        // resources to be displayed.
        if (! empty($resources)) {
            $this->resources($resources);
        }
    }

    /**
     * Setting links.
     *
     * @return void
     */
    protected function settings()
    {
        $acl        = $this->acl;
        $menu       = $this->menu;
        $translator = $this->translator;

        // Add menu when logged-user user has authorization to
        // `manage users`
        if ($acl->can('manage-users')) {
            $menu->add('users')
                ->title($translator->trans('orchestra/foundation::title.users.list'))
                ->link(App::handles('orchestra::users'));
        }

        // Add menu when logged-in user has authorization to
        // `manage orchestra`
        if ($acl->can('manage-orchestra')) {
            $menu->add('extensions', '>:home')
                ->title($translator->trans('orchestra/foundation::title.extensions.list'))
                ->link(App::handles('orchestra::extensions'));

            $menu->add('settings')
                ->title($translator->trans('orchestra/foundation::title.settings.list'))
                ->link(App::handles('orchestra::settings'));
        }
    }

    /**
     * Resources links.
     *
     * @param  array    $resources
     * @return void
     */
    protected function resources($resources)
    {
        $menu       = $this->menu;
        $translator = $this->translator;

        $boot = function ($menu, $translator) {
            $menu->add('resources', '>:extensions')
                ->title($translator->trans('orchestra/foundation::title.resources.list'))
                ->link(App::handles('orchestra::resources'));
        };

        foreach ($resources as $name => $option) {
            if (false === value($option->visible)) {
                continue;
            }

            if (! is_null($boot)) {
                $boot($menu, $translator);
                $boot = null;
            }

            $menu->add($name, '^:resources')
                ->title($option->name)
                ->link(App::handles("orchestra::resources/{$name}"));
        }
    }
}
