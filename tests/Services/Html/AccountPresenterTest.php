<?php namespace Orchestra\Foundation\Tests\Services\Html;

use Mockery as m;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Foundation\Services\Html\AccountPresenter;

class AccountPresenterTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Foundation\Services\Html\AccountPresenter::profileForm() 
	 * method.
	 *
	 * @test
	 */
	public function testProfileFormMethod()
	{
		$model    = new \Illuminate\Support\Fluent();
		$form     = m::mock('FormBuilder');
		$fieldset = m::mock('FieldsetBuilder');
		$control  = m::mock('ControlBuilder');

		$control->shouldReceive('label')->twice()->andReturn(null);

		$fieldset->shouldReceive('control')->twice()->with('input:text', m::any(), m::type('Closure'))->andReturnUsing(
				function ($t, $n, $c) use ($control)
				{
					$c($control);
				});
		$form->shouldReceive('with')->once()->with($model)->andReturn(null)
			->shouldReceive('attributes')->once()->with(array('url' => 'foo', 'method' => 'POST'))->andReturn(null)
			->shouldReceive('hidden')->once()->with('id')->andReturn(null)
			->shouldReceive('fieldset')->once()->with(m::type('Closure'))->andReturnUsing(
				function ($c) use ($fieldset)
				{
					$c($fieldset);
				});

		\Orchestra\Support\Facades\Form::shouldReceive('of')->once()
			->with('orchestra.account', m::type('Closure'))->andReturnUsing(
				function ($f, $c) use ($form)
				{
					$c($form);
					return 'foo';
				});

		$this->assertEquals('foo', AccountPresenter::profileForm($model, 'foo'));
	}

	/**
	 * Test Orchestra\Foundation\Services\Html\AccountPresenter::passwordForm() 
	 * method.
	 *
	 * @test
	 */
	public function testPasswordFormMethod()
	{
		$model    = new \Illuminate\Support\Fluent();
		$form     = m::mock('FormBuilder');
		$fieldset = m::mock('FieldsetBuilder');
		$control  = m::mock('ControlBuilder');

		$control->shouldReceive('label')->times(3)->andReturn(null);

		$fieldset->shouldReceive('control')->times(3)->with('input:password', m::any(), m::type('Closure'))->andReturnUsing(
				function ($t, $n, $c) use ($control)
				{
					$c($control);
				});
		$form->shouldReceive('with')->once()->with($model)->andReturn(null)
			->shouldReceive('attributes')->once()
				->with(array('url' => handles('orchestra/foundation::account/password'), 'method' => 'POST'))->andReturn(null)
			->shouldReceive('hidden')->once()->with('id')->andReturn(null)
			->shouldReceive('fieldset')->once()->with(m::type('Closure'))->andReturnUsing(
				function ($c) use ($fieldset)
				{
					$c($fieldset);
				});

		\Orchestra\Support\Facades\Form::shouldReceive('of')->once()
			->with('orchestra.account: password', m::type('Closure'))->andReturnUsing(
				function ($f, $c) use ($form)
				{
					$c($form);
					return 'foo';
				});

		$this->assertEquals('foo', AccountPresenter::passwordForm($model, 'foo'));
	}
}
