<?php namespace Orchestra\Foundation\Tests\Services\Html;

use Mockery as m;
use Orchestra\Services\TestCase;
use Orchestra\Services\Html\ResourcePresenter;

class ResourcePresenterTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Services\Html\ResourcePresenter::table() method.
	 *
	 * @test
	 */
	public function testTableMethod()
	{
		$model  = new \Illuminate\Support\Fluent();
		$table  = m::mock('TableBuilder');
		$column = m::mock('ColumnBuilder');
		$value  = (object) array(
			'id'   => 'foo',
			'name' => 'Foobar'
		);

		$column->shouldReceive('escape')->once()->with(false)->andReturn(null)
			->shouldReceive('value')->once()->with(m::type('Closure'))->andReturnUsing(
				function ($c) use ($value)
				{
					$c($value);
				});
		$table->shouldReceive('with')->once()->with($model, false)->andReturn(null)
			->shouldReceive('column')->once()->with('name', m::type('Closure'))->andReturnUsing(
				function ($n, $c) use ($column)
				{
					$c($column);
				});

		\Orchestra\Support\Facades\Table::shouldReceive('of')->once()
			->with('orchestra.resources: list', m::type('Closure'))->andReturnUsing(
				function ($t, $c) use ($table)
				{
					$c($table);
					return 'foo';
				});
		\Illuminate\Support\Facades\HTML::shouldReceive('link')->once()
			->with(handles("orchestra/foundation::resources/foo"), e("Foobar"))->andReturn('foo');
		\Illuminate\Support\Facades\HTML::shouldReceive('create')->once()
			->with('strong', 'Foobar')->andReturn('foo');
		\Illuminate\Support\Facades\HTML::shouldReceive('raw')->once()
			->with('foo')->andReturn('Foobar');
		
		$this->assertEquals('foo', ResourcePresenter::table($model));
	}
}
