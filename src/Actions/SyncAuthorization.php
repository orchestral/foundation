<?php

namespace Orchestra\Foundation\Actions;

use Illuminate\Contracts\Foundation\Application;
use Orchestra\Contracts\Authorization\Authorization;

class SyncAuthorization
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The authorization implementation.
     *
     * @var \Orchestra\Contracts\Authorization\Authorization
     */
    protected $acl;

    /**
     * Construct a new class.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Orchestra\Contracts\Authorization\Authorization  $acl
     */
    public function __construct(Application $app, Authorization $acl)
    {
        $this->app = $app;
        $this->acl = $acl;
    }

    /**
     * Re-sync administrator access control.
     *
     * @return void
     */
    public function __invoke()
    {
        $admin = $this->app->make('orchestra.role')->admin();

        $this->acl->allow($admin->name, ['Manage Users', 'Manage Orchestra', 'Manage Roles', 'Manage Acl']);
    }
}
