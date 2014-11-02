<?php namespace Orchestra\Foundation\Filters\TestCase;

use Mockery as m;
use Orchestra\Foundation\Filters\CanBeInstalled;

class CanBeInstalledTest extends \PHPUnit_Framework_TestCase
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
     * method can be installed.
     *
     * @test
     */
    public function testFilterMethodCanBeInstalled()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');

        $foundation->shouldReceive('installed')->once()->andReturn(false)
            ->shouldReceive('handles')->once()->with('orchestra::install')->andReturn('http://localhost/admin/install');

        $stub = new CanBeInstalled($foundation);

        $this->assertInstanceOf('\Illuminate\Http\RedirectResponse', $stub->filter());
    }

    /**
     * Test Orchestra\Foundation\Filters\CanBeInstalled::filter()
     * method can't be installed.
     *
     * @test
     */
    public function testFilterMethodCantBeInstalled()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');

        $foundation->shouldReceive('installed')->once()->andReturn(true);

        $stub = new CanBeInstalled($foundation);

        $this->assertNull($stub->filter());
    }
}
