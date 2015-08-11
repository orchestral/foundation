<?php namespace Orchestra\Foundation\Http\Filters\TestCase;

use Mockery as m;
use Orchestra\Foundation\Http\Filters\CanManage;

class CanManageTest extends \PHPUnit_Framework_TestCase
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
     * method when can manage.
     *
     * @test
     */
    public function testFilterMethodWhenCanManage()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $auth = m::mock('\Illuminate\Contracts\Auth\Guard');
        $config = m::mock('\Illuminate\Contracts\Config\Repository');
        $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization');

        $route = m::mock('\Illuminate\Routing\Route');
        $request = m::mock('\Illuminate\Http\Request');

        $foundation->shouldReceive('acl')->once()->andReturn($acl)
            ->shouldReceive('handles')->once()->with('orchestra::login')->andReturn('http://localhost/admin/login');
        $acl->shouldReceive('can')->once()->with('manage-orchestra')->andReturn(false);
        $auth->shouldReceive('guest')->once()->andReturn(true);
        $config->shouldReceive('get')->once()->with('orchestra/foundation::routes.guest')->andReturn('orchestra::login');

        $stub = new CanManage($foundation, $auth, $config);

        $this->assertInstanceOf('\Illuminate\Http\RedirectResponse', $stub->filter($route, $request, 'orchestra'));
    }

    /**
     * Test Orchestra\Foundation\Filters\IsInstalled::filter()
     * method when can't manage.
     *
     * @test
     */
    public function testFilterMethodWhenCantManage()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $auth = m::mock('\Illuminate\Contracts\Auth\Guard');
        $config = m::mock('\Illuminate\Contracts\Config\Repository');
        $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization');

        $route = m::mock('\Illuminate\Routing\Route');
        $request = m::mock('\Illuminate\Http\Request');

        $foundation->shouldReceive('acl')->once()->andReturn($acl);
        $acl->shouldReceive('can')->once()->with('manage-foo')->andReturn(true);

        $stub = new CanManage($foundation, $auth, $config);

        $this->assertNull($stub->filter($route, $request, 'foo'));
    }
}
