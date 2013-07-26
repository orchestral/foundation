<?php

use Orchestra\Model\Role;
use Orchestra\Foundation\Services\Event\RoleObserver;

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
