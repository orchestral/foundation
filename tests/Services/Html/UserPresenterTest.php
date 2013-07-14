<?php namespace Orchestra\Foundation\Tests\Services\Html;

use Mockery as m;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Foundation\Services\Html\UserPresenter;

class UserPresenterTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Foundation\Services\Html\UserPresenter::table() method.
	 *
	 * @test
	 */
	public function testTableMethod()
	{
		$model  = new \Illuminate\Support\Fluent();
		$table  = m::mock('TableBuilder');
		$column = m::mock('ColumnBuilder');
		$value  = (object) array(
			'fullname' => 'Foo',
			'roles'    => array(
				(object) array('id' => 1, 'name' => 'Administrator'),
				(object) array('id' =>2, 'name' => 'Member'),
			)
		);

		$column->shouldReceive('label')->twice()->andReturn(null)
			->shouldReceive('escape')->once()->with(false)->andReturn(null)
			->shouldReceive('value')->once()->with(m::type('Closure'))->andReturnUsing(
				function ($c) use ($value)
				{
					$c($value);
				});
		$table->shouldReceive('with')->once()->with($model, true)->andReturn(null)
			->shouldReceive('layout')->once()->with('orchestra/foundation::components.table')->andReturn(null)
			->shouldReceive('column')->once()->with('fullname', m::type('Closure'))->andReturnUsing(
				function ($n, $c) use ($column)
				{
					$c($column);
				})
			->shouldReceive('column')->once()->with('email', m::type('Closure'))->andReturnUsing(
				function ($n, $c) use ($column)
				{
					$c($column);
				});

		\Orchestra\Support\Facades\Table::shouldReceive('of')->once()
			->with('orchestra.users', m::type('Closure'))->andReturnUsing(
				function ($t, $c) use ($table)
				{
					$c($table);
					return 'foo';
				});
		\Illuminate\Support\Facades\HTML::shouldReceive('create')->once()
				->with('span', 'Administrator', m::any())->andReturn('administrator')
			->shouldReceive('create')->once()
				->with('span', 'Member', m::any())->andReturn('member')
			->shouldReceive('create')->once()
				->with('strong', 'Foo')->andReturn('Foo')
			->shouldReceive('create')->once()->with('br')->andReturn('')
			->shouldReceive('create')->once()->with('span', 'raw-foo', m::any())->andReturn(null)
			->shouldReceive('raw')->once()->with('administrator member')->andReturn('raw-foo');
		
		$this->assertEquals('foo', UserPresenter::table($model));
	}

	/**
	 * Test Orchestra\Foundation\Services\Html\UserPresenter::actions() 
	 * method.
	 *
	 * @test
	 */
	public function testActionsMethod()
	{
		$model   = new \Illuminate\Support\Fluent();
		$builder = m::mock('\Orchestra\Html\Table\TableBuilder');
		$table   = m::mock('TableGenerator');
		$column  = m::mock('ColumnBuilder');
		$value  = (object) array(
			'id'   => 1,
			'name' => 'Foo',
		);

		$column->shouldReceive('label')->once()->with('')->andReturn(null)
			->shouldReceive('escape')->once()->with(false)->andReturn(null)
			->shouldReceive('headers')->once()->with(m::type('Array'))->andReturn(null)
			->shouldReceive('value')->once()->with(m::type('Closure'))->andReturnUsing(
				function ($c) use ($value)
				{
					$c($value);
				});
		$table->shouldReceive('column')->once()->with('action', m::type('Closure'))->andReturnUsing(
			function ($n, $c) use ($column)
			{
				$c($column);
			});

		$builder->shouldReceive('extend')->once()->with(m::type('Closure'))->andReturnUsing(
			function ($c) use ($table)
			{
				$c($table);
				return 'foo';
			});

		\Illuminate\Support\Facades\HTML::shouldReceive('link')->once()
			->with(handles("orchestra/foundation::users/1/edit"), m::any(), m::type('Array'))->andReturn('edit');
		\Illuminate\Support\Facades\HTML::shouldReceive('link')->once()
			->with(handles("orchestra/foundation::users/1/delete"), m::any(), m::type('Array'))->andReturn('delete');
		\Illuminate\Support\Facades\HTML::shouldReceive('raw')->once()
			->with('editdelete')->andReturn('raw-edit');
		\Illuminate\Support\Facades\HTML::shouldReceive('create')->once()
			->with('div', 'raw-edit', m::type('Array'))->andReturn('create-div');
		\Illuminate\Support\Facades\Auth::shouldReceive('user')->once()
			->andReturn((object) array('id' => 2));

		$this->assertEquals('foo', UserPresenter::actions($builder));
	}

	/**
	 * Test Orchestra\Foundation\Services\Html\UserPresenter::form() method.
	 *
	 * @test
	 */
	public function testFormMethod()
	{
		$model    = new \Illuminate\Support\Fluent(array(
			'id' => 1,
		));
		$form     = m::mock('FormBuilder');
		$fieldset = m::mock('FieldsetBuilder');
		$control  = m::mock('ControlBuilder');
		$value    = (object) array(
			'roles' => array(
				(object) array('id' => 1, 'name' => 'Administrator'),
				(object) array('id' => 2, 'name' => 'Member'),
			),
		);

		$control->shouldReceive('label')->times(4)->andReturn(null)
			->shouldReceive('options')->once()->with('roles')->andReturn(null)
			->shouldReceive('attributes')->once()->with(m::type('Array'))->andReturn(null)
			->shouldReceive('value')->once()->with(m::type('Closure'))->andReturnUsing(
				function($c) use ($value)
				{
					$c($value);
				});
		$fieldset->shouldReceive('control')->twice()
				->with('input:text', m::any(), m::type('Closure'))->andReturnUsing(
					function ($t, $n, $c) use ($control)
					{
						$c($control);
					})
			->shouldReceive('control')->once()
				->with('input:password', 'password', m::type('Closure'))->andReturnUsing(
					function ($t, $n, $c) use ($control)
					{
						$c($control);
					})
			->shouldReceive('control')->once()
				->with('select', 'roles[]', m::type('Closure'))->andReturnUsing(
					function ($t, $n, $c) use ($control)
					{
						$c($control);
					});
		$form->shouldReceive('with')->once()->andReturn(null)
			->shouldReceive('layout')->once()->with('orchestra/foundation::components.form')->andReturn(null)
			->shouldReceive('attributes')->once()
				->with(array('url' => handles('orchestra/foundation::users/1'), 'method' => 'PUT'))->andReturn(null)
			->shouldReceive('hidden')->once()->with('id')->andReturn(null)
			->shouldReceive('fieldset')->once()->with(m::type('Closure'))->andReturnUsing(
				function ($c) use ($fieldset)
				{
					$c($fieldset);
				});
		\Orchestra\Support\Facades\Form::shouldReceive('of')->once()
			->with('orchestra.users', m::type('Closure'))->andReturnUsing(
				function ($n, $c) use ($form)
				{
					$c($form);
					return 'foo';
				});
		$app = \Orchestra\Support\Facades\App::getFacadeApplication();

		$app['orchestra.role'] = $roles = m::mock('Role');
		$roles->shouldReceive('lists')->once()->with('name', 'id')->andReturn('roles');
		
		$this->assertEquals('foo', UserPresenter::form($model, 'update'));
	}
}
