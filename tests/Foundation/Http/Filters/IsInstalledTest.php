<?php namespace Orchestra\Foundation\Http\Filters\TestCase;

use Mockery as m;
use Orchestra\Foundation\Http\Filters\IsInstalled;

class IsInstalledTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Filters\IsInstalled::filter()
     * method when installed.
     *
     * @test
     */
    public function testFilterMethodWhenInstalled()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $auth = m::mock('\Illuminate\Contracts\Auth\Guard');
        $config = m::mock('\Illuminate\Contracts\Config\Repository');

        $foundation->shouldReceive('installed')->once()->andReturn(true)
            ->shouldReceive('handles')->once()->with('orchestra::login')->andReturn('http://localhost/admin/login');
        $auth->shouldReceive('guest')->once()->andReturn(true);
        $config->shouldReceive('get')->once()->with('orchestra/foundation::routes.guest')->andReturn('orchestra::login');

        $stub = new IsInstalled($foundation, $auth, $config);

        $this->assertInstanceOf('\Illuminate\Http\RedirectResponse', $stub->filter());
    }

    /**
     * Test Orchestra\Foundation\Filters\IsInstalled::filter()
     * method when not installed.
     *
     * @test
     */
    public function testFilterMethodWhenNotInstalled()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $auth = m::mock('\Illuminate\Contracts\Auth\Guard');
        $config = m::mock('\Illuminate\Contracts\Config\Repository');

        $foundation->shouldReceive('installed')->once()->andReturn(false);

        $stub = new IsInstalled($foundation, $auth, $config);

        $this->assertNull($stub->filter());
    }
}
