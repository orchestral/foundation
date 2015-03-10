<?php namespace Orchestra\Foundation\Bootstrap\TestCase;

use Mockery as m;
use Illuminate\Foundation\Application;
use Orchestra\Foundation\Bootstrap\LoadFoundation;

class LoadFoundationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Bootstrap\NotifyIfSafeMode::bootstrap()
     * method.
     *
     * @test
     */
    public function testBootstrapMethod()
    {
        $app = new Application(__DIR__);

        $app['orchestra.app'] = $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');

        $foundation->shouldReceive('boot')->once()->andReturnNull();

        (new LoadFoundation())->bootstrap($app);
    }
}
