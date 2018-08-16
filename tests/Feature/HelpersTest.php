<?php

namespace Orchestra\Tests\Feature;

use Mockery as m;
use Orchestra\Foundation\Testing\Installation;

class HelperTest extends TestCase
{
    use Installation;

    /**
     * @test
     */
    public function it_can_use_orchestra_helper()
    {
        $this->assertInstanceOf('\Orchestra\Contracts\Foundation\Foundation', orchestra());
        $this->assertInstanceOf('\Orchestra\Contracts\Memory\Provider', orchestra('memory'));
    }

    /**
     * @test
     */
    public function it_can_use_memorize_helper()
    {
        $this->assertEquals('My Application', memorize('site.name'));
        $this->assertEquals('Laravel', memorize('site.platform', 'Laravel'));
    }

    /**
     * @test
     */
    public function it_can_use_handles_helper()
    {
        $this->assertEquals('http://localhost/foo', handles('app::foo'));
        $this->assertEquals('http://localhost/admin/login', handles('orchestra::login'));
    }

    /**
     * @test
     */
    public function it_can_use_get_meta_helper()
    {
        $this->instance('orchestra.meta', $meta = m::mock('\Orchestra\Foundation\Meta'));

        $meta->shouldReceive('get')->once()->with('title', 'foo')->andReturn('foobar');

        $this->assertEquals('foobar', get_meta('title', 'foo'));
    }

    /**
     * @test
     */
    public function it_can_use_set_meta_helper()
    {
        $this->assertEquals(['title' => 'foo'], set_meta('title', 'foo'));

        $this->assertSame('foo', get_meta('title'));
    }
}
