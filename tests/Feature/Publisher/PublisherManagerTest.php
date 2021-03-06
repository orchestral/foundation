<?php

namespace Orchestra\Tests\Feature\Publisher;

use Mockery as m;
use Orchestra\Foundation\Publisher\PublisherManager;
use Orchestra\Foundation\Testing\Installation;
use Orchestra\Tests\Feature\TestCase;

class PublisherManagerTest extends TestCase
{
    use Installation;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_get_default_driver()
    {
        $this->instance('orchestra.publisher.filesystem', $client = m::mock('\Orchestra\Contracts\Publisher\Uploader'));

        $memory = m::mock('\Orchestra\Contracts\Memory\Provider');
        $memory->shouldReceive('get')->once()->with('orchestra.publisher.driver', 'filesystem')->andReturn('filesystem');

        $stub = (new PublisherManager($this->app))->attach($memory);

        $this->assertInstanceOf(
            '\Orchestra\Contracts\Publisher\Uploader', $stub->driver()
        );
    }

    /** @test */
    public function it_can_execute_publisher()
    {
        $this->instance('orchestra.publisher.filesystem', $client = m::mock('\Orchestra\Contracts\Publisher\Uploader'));

        $client->shouldReceive('upload')->with('a')->andReturnTrue()
            ->shouldReceive('upload')->with('b')->andThrow('\Exception');

        $memory = m::mock('\Orchestra\Contracts\Memory\Provider');
        $memory->shouldReceive('get')->once()->with('orchestra.publisher.queue', [])->andReturn(['a', 'b'])
            ->shouldReceive('get')->times(2)->with('orchestra.publisher.driver', 'filesystem')->andReturn('filesystem')
            ->shouldReceive('put')->once()->with('orchestra.publisher.queue', ['b'])->andReturn(true);

        $stub = (new PublisherManager($this->app))->attach($memory);

        $this->assertTrue($stub->execute());
    }

    /** @test */
    public function it_can_queue_publisher()
    {
        $memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        $memory->shouldReceive('get')->once()->with('orchestra.publisher.queue', [])
                ->andReturn(['foo', 'foobar'])
            ->shouldReceive('put')->once()->with('orchestra.publisher.queue', m::any())
                ->andReturnNull();

        $stub = (new PublisherManager($this->app))->attach($memory);

        $this->assertTrue($stub->queue(['foo', 'bar']));
    }

    /** @test */
    public function it_can_get_queued_publishes()
    {
        $memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        $memory->shouldReceive('get')->once()->with('orchestra.publisher.queue', [])->andReturn(['foo']);

        $stub = (new PublisherManager($this->app))->attach($memory);

        $this->assertEquals(['foo'], $stub->queued());
    }
}
