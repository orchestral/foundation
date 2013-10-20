<?php namespace Orchestra\Foundation\Presenter\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Table;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Foundation\Presenter\Resource;

class ResourceTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
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
        $model  = new Fluent;
        $table  = m::mock('TableBuilder');
        $column = m::mock('ColumnBuilder');
        $value  = (object) array(
            'id'   => 'foo',
            'name' => 'Foobar'
        );

        $column->shouldReceive('escape')->once()->with(false)->andReturn(null)
            ->shouldReceive('value')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($value) {
                    $c($value);
                });
        $table->shouldReceive('with')->once()->with($model, false)->andReturn(null)
            ->shouldReceive('layout')->once()->with('orchestra/foundation::components.table')->andReturn(null)
            ->shouldReceive('column')->once()->with('name', m::type('Closure'))
                ->andReturnUsing(function ($n, $c) use ($column) {
                    $c($column);
                });

        Table::shouldReceive('of')->once()->with('orchestra.resources: list', m::type('Closure'))
            ->andReturnUsing(function ($t, $c) use ($table) {
                $c($table);
                return 'foo';
            });
        HTML::shouldReceive('link')->once()
            ->with(handles("orchestra/foundation::resources/foo"), e("Foobar"))->andReturn('foo');
        HTML::shouldReceive('create')->once()
            ->with('strong', 'Foobar')->andReturn('foo');
        HTML::shouldReceive('raw')->once()
            ->with('foo')->andReturn('Foobar');

        $stub = new Resource;

        $this->assertEquals('foo', $stub->table($model));
    }
}
