<?php namespace Orchestra\Foundation\Bootstrap\TestCase;

use Orchestra\Testing\TestCase;

class LoadUserMetaDataTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     * @return void
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

        $this->assertInstanceOf('\Orchestra\Model\Memory\UserMetaProvider', $stub);
        $this->assertInstanceOf('\Orchestra\Memory\Provider', $stub);
    }
}
