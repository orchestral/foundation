<?php

namespace Integration\TestCase;

use Orchestra\Foundation\Auth\User;
use Orchestra\Installation\Installation;
use Orchestra\Testing\BrowserKit\TestCase as ApplicationTestCase;

abstract class TestCase extends ApplicationTestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../factories');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->make('config')->set(['auth.providers.users.model' => User::class]);
    }

    /**
     * Resolve application implementation.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function resolveApplication()
    {
        $app = parent::resolveApplication();

        $app->useVendorPath(__DIR__.'/../../vendor');

        return $app;
    }

    /**
     * Install Orchestra Platform and get the administrator user.
     *
     * @return \Orchestra\Foundation\Auth\User
     */
    protected function createAdminUser()
    {
        return factory(User::class, 'admin')->create();
    }
}
