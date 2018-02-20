<?php

namespace Orchestra\Tests\Unit\Bootstrap;

use Orchestra\Testing\TestCase;

class LoadUserMetaDataTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->make('Orchestra\Foundation\Bootstrap\LoadUserMetaData')->bootstrap($app);
    }

    /**
     * Test instance of `orchestra.memory`.
     *
     * @test
     */
    public function testInstanceOfOrchestraMemory()
    {
        $stub = $this->app->make('orchestra.memory')->driver('user');

        $this->assertInstanceOf('\Orchestra\Model\Memory\UserProvider', $stub);
        $this->assertInstanceOf('\Orchestra\Memory\Provider', $stub);
    }
}
