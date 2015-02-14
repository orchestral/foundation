<?php namespace Orchestra\Foundation\Http\Handlers;

use Orchestra\Foundation\Support\MenuHandler;
use Orchestra\Contracts\Authorization\Authorization;

class UserMenuHandler extends MenuHandler
{
    /**
     * Menu configuration.
     *
     * @var array
     */
    protected $menu = [
        'id'       => 'users',
        'position' => '*',
        'title'    => 'orchestra/foundation::title.users.list',
        'link'     => 'orchestra::users',
        'icon'     => null,
    ];

    /**
     * Get the title.
     *
     * @param  string  $value
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
     * @return bool
     */
    public function authorize(Authorization $acl)
    {
        return $acl->can('manage-users');
    }
}
