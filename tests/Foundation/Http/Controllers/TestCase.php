<?php namespace Orchestra\Foundation\Http\Controllers\TestCase;

use Orchestra\Testing\TestCase as BaseTestCase;
use Orchestra\Extension\Bootstrap\LoadExtension;

abstract class TestCase extends BaseTestCase
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
        $app->make(LoadExtension::class)->bootstrap($app);
    }
}
