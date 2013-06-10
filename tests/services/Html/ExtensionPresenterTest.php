<?php namespace Orchestra\Foundation\Tests\Services\Html;

use Mockery as m;
use Orchestra\Services\TestCase;
use Orchestra\Services\Html\ExtensionPresenter;

class ExtensionPresenterTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Services\Html\ExtensionPresenter::form() method.
	 *
	 * @test
	 */
	public function testFormMethod()
	{
		$model    = new \Illuminate\Support\Fluent();
		$form     = m::mock('FormBuilder');
		$fieldset = m::mock('FieldsetBuilder');
		$control  = m::mock('ControlBuilder');

		$control->shouldReceive('label')->twice()->andReturn(null)
			->shouldReceive('value')->once()->andReturn(null)
			->shouldReceive('field')->once()->with(m::type('Closure'))->andReturnUsing(
					function ($c)
					{
						$c();
					});
		$fieldset->shouldReceive('control')->twice()
				->with('input:text', m::any(), m::type('Closure'))->andReturnUsing(
					function ($t, $n, $c) use ($control)
					{
						$c($control);
					});
		$form->shouldReceive('with')->once()->with($model)->andReturn(null)
			->shouldReceive('attributes')->once()
				->with(array('url' => handles('orchestra/foundation::extensions/configure/foo.bar'), 'method' => 'POST'))
				->andReturn(null)
			->shouldReceive('fieldset')->once()->with(m::type('Closure'))->andReturnUsing(
				function ($c) use ($fieldset)
				{
					$c($fieldset);
				});

		\Orchestra\Support\Facades\Form::shouldReceive('of')->once()
			->with('orchestra.extension: foo/bar', m::type('Closure'))->andReturnUsing(
				function ($t, $c) use ($form)
				{
					$c($form);
					return 'foo';
				});
		\Orchestra\Support\Facades\Extension::shouldReceive('option')->once()
			->with('foo/bar', 'handles')->andReturn('foo');
		\Illuminate\Support\Facades\HTML::shouldReceive('link')->once()
			->with(handles("orchestra/foundation::extensions/update/foo.bar"), m::any(), m::any())->andReturn('foo');
		
		$this->assertEquals('foo', ExtensionPresenter::form($model, 'foo/bar'));
	}
}
