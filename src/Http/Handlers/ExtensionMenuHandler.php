<?php namespace Orchestra\Foundation\Http\Handlers;

use Orchestra\Foundation\Support\MenuHandler;
use Orchestra\Contracts\Authorization\Authorization;

class ExtensionMenuHandler extends MenuHandler
{
    /**
     * Menu configuration.
     *
     * @var array
     */
    protected $menu = [
        'id'       => 'extensions',
        'position' => '>:home',
        'title'    => 'orchestra/foundation::title.extensions.list',
        'link'     => 'orchestra::extensions',
        'icon'     => null,
    ];

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
     * @param  \Orchestra\Contracts\Authorization\Authorization  $acl
     *
     * @return bool
     */
    public function authorize(Authorization $acl)
    {
        return ($this->container->bound('orchestra.extension') && $acl->can('manage-orchestra'));
    }
}
