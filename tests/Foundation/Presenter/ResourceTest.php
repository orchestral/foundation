<?php namespace Orchestra\Foundation\Presenter\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Fluent;
use Orchestra\Foundation\Presenter\Resource;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->app = new Container;

        $this->app['orchestra.app'] = m::mock('\Orchestra\Foundation\Application')->makePartial();
        $this->app['translator'] = m::mock('\Illuminate\Translation\Translator')->makePartial();

        $this->app['orchestra.app']->shouldReceive('handles');
        $this->app['translator']->shouldReceive('trans');

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this->app);
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        unset($this->app);
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Presenter\Resource::table()
     * method.
     *
     * @test
     */
    public function testTableMethod()
    {
        $app    = $this->app;
        $model  = new Fluent;
        $grid   = m::mock('\Orchestra\Html\Table\Grid')->makePartial();
        $column = m::mock('\Orchestra\Html\Table\Column')->makePartial();
        $value  = (object) array(
            'id'   => 'foo',
            'name' => 'Foobar'
        );

        $stub = new Resource;

        $column->shouldReceive('escape')->once()->with(false)->andReturnSelf()
            ->shouldReceive('value')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($value) {
                    $c($value);
                });
        $grid->shouldReceive('with')->once()->with($model, false)->andReturnNull()
            ->shouldReceive('layout')->once()->with('orchestra/foundation::components.table')->andReturnNull()
            ->shouldReceive('column')->once()->with('name')->andReturn($column);

        $app['orchestra.table'] = m::mock('\Orchestra\Html\Table\Factory')->makePartial();
        $app['html'] = m::mock('\Orchestra\Html\HtmlBuilder')->makePartial();

        $app['orchestra.table']->shouldReceive('of')->once()
                ->with('orchestra.resources: list', m::type('Closure'))
                ->andReturnUsing(function ($t, $c) use ($grid) {
                    $c($grid);
                    return 'foo';
                });
        $app['html']->shouldReceive('create')->once()->with('strong', 'Foobar')->andReturn('foo')
            ->shouldReceive('raw')->once()->with('foo')->andReturn('Foobar')
            ->shouldReceive('link')->once()
                ->with(handles("orchestra/foundation::resources/foo"), e("Foobar"))->andReturn('foo');

        $this->assertEquals('foo', $stub->table($model));
    }
}
