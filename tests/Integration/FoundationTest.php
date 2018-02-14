<?php

namespace Orchestra\Tests\Integration;

use Mockery as m;
use Illuminate\Http\Request;
use Orchestra\Extension\UrlGenerator;

class FoundationTest extends TestCase
{
    /** @test */
    public function it_booted_after_installed()
    {
        $this->install();

        $this->assertTrue($this->app['orchestra.installed']);
        $this->assertInstanceOf('\Orchestra\Widget\WidgetManager', $this->app['orchestra.widget']);
        $this->assertInstanceOf('\Orchestra\Authorization\Factory', $this->app['orchestra.acl']);
        $this->assertInstanceOf('\Orchestra\Memory\MemoryManager', $this->app['orchestra.memory']);
        $this->assertTrue($this->app['orchestra.app']->installed());
    }

    /** @test */
    public function it_boot_without_being_installed()
    {
        $this->assertFalse($this->app['orchestra.installed']);
        $this->assertFalse($this->app['orchestra.app']->installed());
    }

    /** @test */
    public function it_generate_routes_properly()
    {
        $foundation = $this->app['orchestra.app'];
        $this->app['request'] = $request = m::mock('\Illuminate\Http\Request');

        $request->shouldReceive('root')->andReturn('http://localhost')
            ->shouldReceive('getScheme')->andReturn('http');

        $this->app->instance('orchestra.extension.url', new UrlGenerator($request));

        $this->assertEquals('http://localhost', $foundation->handles('app::/'));
        $this->assertEquals('http://localhost/info?foo=bar', $foundation->handles('info?foo=bar'));
        $this->assertEquals('http://localhost/admin/installer', $foundation->handles('orchestra::installer'));
        $this->assertEquals('http://localhost/admin/installer', $foundation->handles('orchestra::installer/'));
    }

    /**
     * @test
     * @dataProvider dataProviderForIs
     */
    public function it_validate_routes_properly($url, $expected)
    {
        $this->install();
        $this->app['request'] = $request = m::mock('\Illuminate\Http\Request');

        $request->shouldReceive('root')->andReturn('http://localhost')
            ->shouldReceive('getScheme')->andReturn('http')
            ->shouldReceive('path')->andReturn($url);

        $this->app->instance('orchestra.extension.url', new UrlGenerator($request));
        $this->app['orchestra.extension.url']->handle($url);

        $this->assertTrue($this->app['orchestra.app']->is(...$expected));
    }

    public function dataProviderForIs()
    {
        return [
            ['/', ['app::/']],
            ['info', ['info']],
            ['info', ['app::info']],
            //['admin/login', ['orchestra::login']],
            //['info?foo=bar', 'info?foo=bar'],
            //['/admin/login', 'orchestra::login'],
        ];
    }
}
