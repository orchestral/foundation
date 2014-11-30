<?php namespace Orchestra\Foundation;

use Illuminate\Translation\Translator;
use Orchestra\Resources\Factory as Resources;
use Orchestra\Contracts\Foundation\Foundation as FoundationContract;

class AdminMenuHandler
{
    /**
     * Kernel instance.
     *
     * @var \Orchestra\Contracts\Foundation\Foundation
     */
    protected $foundation;

    /**
     * ACL instance.
     *
     * @var \Orchestra\Contracts\Auth\Authorization
     */
    protected $acl;

    /**
     * Menu instance.
     *
     * @var \Orchestra\Widget\Handlers\Menu
     */
    protected $menu;

    /**
     * Resources instance.
     *
     * @var \Orchestra\Resources\Factory
     */
    protected $resources;

    /**
     * Translator instance.
     *
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * Construct a new handler.
     *
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     * @param  \Orchestra\Resources\Factory  $resources
     * @param  \Illuminate\Translation\Translator  $translator
     */
    public function __construct(FoundationContract $foundation, Resources $resources, Translator $translator)
    {
        $this->foundation = $foundation;
        $this->menu = $foundation->menu();
        $this->acl = $foundation->acl();
        $this->resources = $resources;
        $this->translator = $translator;
    }

    /**
     * Create a handler for `orchestra.ready: admin` event.
     *
     * @return void
     */
    public function handle()
    {
        $this->settings();

        $repository = $this->resources->all();

        // Resources menu should only be appended if there is actually
        // resources to be displayed.
        if (! empty($repository)) {
            $this->resources($repository);
        }
    }

    /**
     * Setting links.
     *
     * @return void
     */
    protected function settings()
    {
        // Add menu when logged-user user has authorization to
        // `manage users`
        if ($this->acl->can('manage-users')) {
            $this->menu->add('users')
                ->title($this->translator->trans('orchestra/foundation::title.users.list'))
                ->link($this->foundation->handles('orchestra::users'));
        }

        // Add menu when logged-in user has authorization to
        // `manage orchestra`
        if ($this->acl->can('manage-orchestra')) {
            if ($this->foundation->bound('orchestra.extension')) {
                $this->menu->add('extensions', '>:home')
                    ->title($this->translator->trans('orchestra/foundation::title.extensions.list'))
                    ->link($this->foundation->handles('orchestra::extensions'));
            }

            $this->menu->add('settings')
                ->title($this->translator->trans('orchestra/foundation::title.settings.list'))
                ->link($this->foundation->handles('orchestra::settings'));
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
                $boot($this->foundation, $this->menu, $this->translator);
                $boot = null;
            }

            $this->menu->add($name, '^:resources')
                ->title($option->name)
                ->link($this->foundation->handles("orchestra::resources/{$name}"));
        }
    }
}
