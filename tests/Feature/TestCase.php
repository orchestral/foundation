<?php

namespace Orchestra\Tests\Feature;

use Orchestra\Foundation\Auth\User;
use Orchestra\Testing\TestCase as ApplicationTestCase;

abstract class TestCase extends ApplicationTestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->make('config')->set([
            'orchestra/memory::driver' => 'runtime.default',
            'auth.providers.users.model' => User::class,
        ]);
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
     * Create user with member permission.
     *
     * @param  array  $attributes
     *
     * @return \Orchestra\Foundation\Auth\User
     */
    protected function createUserAsMember(array $attributes = [])
    {
        return User::faker()->states('member')->create($attributes);
    }
}
