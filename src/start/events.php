<?php

use Orchestra\Model\Role;
use Orchestra\Model\Observer\RoleObserver;

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
Role::setDefaultRoles(Config::get('orchestra/foundation::roles'));
