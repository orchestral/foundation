<?php

namespace Orchestra\Foundation\Testing;

trait Installation
{
    /**
     * The administrator user.
     *
     * @var \Orchestra\Foundation\Auth\User|null
     */
    protected $adminUser;

    /**
     * Define hooks to run installation before each test and run rollback on uninstall.
     *
     * @return void
     */
    public function beginInstallation()
    {
        $this->adminUser = $this->install();
    }
}
