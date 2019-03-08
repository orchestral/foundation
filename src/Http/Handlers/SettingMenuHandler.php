<?php

namespace Orchestra\Foundation\Http\Handlers;

use Orchestra\Foundation\Support\MenuHandler;
use Orchestra\Contracts\Authorization\Authorization;

class SettingMenuHandler extends MenuHandler
{
    /**
     * Menu configuration.
     *
     * @var array
     */
    protected $menu = [
        'id' => 'settings',
        'position' => '*',
        'title' => 'orchestra/foundation::title.settings.list',
        'link' => 'orchestra::settings',
        'icon' => 'cogs',
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
        return \trans($value);
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
        return $acl->canIf('manage-orchestra');
    }
}
