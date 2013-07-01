<?php

use Orchestra\Model\Role;
use Orchestra\Foundation\Services\Event\RoleObserver;

Role::observe(new RoleObserver);
