<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Fluent;
use Orchestra\Foundation\Processor\ResourceLoader;

class ResourceLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Processor\ResourceLoader::showAll()
     * method.
     *
     * @test
     */
    public function testShowAllMethod()
    {
        $listener  = m::mock('\Orchestra\Contracts\Foundation\Listener\ResourceLoader');
        $presenter = m::mock('\Orchestra\Foundation\Http\Presenters\Resource');
        $resources = m::mock('\Orchestra\Resources\Factory');

        $data = [
            'laravel' => new Fluent(['visible' => true, 'name' => 'Laravel']),
        ];

        $stub = new ResourceLoader($presenter, $resources);

        $resources->shouldReceive('all')->once()->andReturn($data);
        $presenter->shouldReceive('table')->once()->with(m::type('Array'))->andReturn('table');
        $listener->shouldReceive('showResourcesList')->once()
            ->with(m::type('Array'))->andReturn('show.all');

        $this->assertEquals('show.all', $stub->index($listener));
    }

    /**
     * Test Orchestra\Foundation\Processor\ResourceLoader::showAll()
     * method.
     *
     * @test
     */
    public function testRequestMethod()
    {
        $listener  = m::mock('\Orchestra\Contracts\Foundation\Listener\ResourceLoader');
        $presenter = m::mock('\Orchestra\Foundation\Http\Presenters\Resource');
        $resources = m::mock('\Orchestra\Resources\Factory');

        $data = [
            'laravel' => new Fluent(['visible' => true, 'name' => 'Laravel']),
        ];

        $stub = new ResourceLoader($presenter, $resources);

        $resources->shouldReceive('all')->once()->andReturn($data)
            ->shouldReceive('call')->once()->with('laravel', [])->andReturn('Laravel')
            ->shouldReceive('response')->once()->with('Laravel', m::type('Closure'))
                ->andReturnUsing(function ($n, $c) {
                    return $c('Laravel');
                });
        $listener->shouldReceive('onRequestSucceed')->once()
            ->with(m::type('Array'))->andReturn('request.succeed');

        $this->assertEquals('request.succeed', $stub->show($listener, 'laravel'));
    }
}
