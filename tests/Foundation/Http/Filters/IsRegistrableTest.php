<?php namespace Orchestra\Foundation\Http\Filters\TestCase;

use Mockery as m;
use Orchestra\Foundation\Http\Filters\IsRegistrable;

class IsRegistrableTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Filters\CanBeInstalled::filter()
     * method can be registered.
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testFilterMethodCanBeRegistered()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        $foundation->shouldReceive('memory')->once()->andReturn($memory);
        $memory->shouldReceive('get')->once()->with('site.registrable', false)->andReturn(false);

        $stub = new IsRegistrable($foundation);

        $stub->filter();
    }

    /**
     * Test Orchestra\Foundation\Filters\CanBeInstalled::filter()
     * method can't be registered.
     *
     * @test
     */
    public function testFilterMethodCantBeRegistered()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        $foundation->shouldReceive('memory')->once()->andReturn($memory);
        $memory->shouldReceive('get')->once()->with('site.registrable', false)->andReturn(true);

        $stub = new IsRegistrable($foundation);

        $this->assertNull($stub->filter());
    }
}
