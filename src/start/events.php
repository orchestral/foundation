<?php

use Orchestra\Model\Role;
use Orchestra\Services\Event\RoleObserver;

Role::observe(new RoleObserver);
