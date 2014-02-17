<?php

use Illuminate\Support\Facades\Config;
use Orchestra\Model\Role;
use Orchestra\Model\Observer\Role as RoleObserver;

/*
|--------------------------------------------------------------------------
| Observe Role Eloquent Events
|--------------------------------------------------------------------------
|
| Orchestra Platform should be able to watch any changes to Role model
| to be able to sync those information to Orchestra\Acl.
|
*/

Role::observe(new RoleObserver);

/*
|--------------------------------------------------------------------------
| Set Default Roles
|--------------------------------------------------------------------------
|
| Orchestra Platform should be able to determine admin and member roles
| dynamically.
|
*/

Role::setDefaultRoles(Config::get('orchestra/foundation::roles'));
