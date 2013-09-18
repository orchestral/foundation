<?php namespace Orchestra\Foundation\Services\Html\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Extension;
use Orchestra\Support\Facades\Form;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Foundation\Services\Html\ExtensionPresenter;

class ExtensionPresenterTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Foundation\Services\Html\ExtensionPresenter::form() 
	 * method.
	 *
	 * @test
	 */
	public function testFormMethod()
	{
		$model     = new Fluent;
		$form      = m::mock('FormBuilder');
		$fieldset  = m::mock('FieldsetBuilder');
		$control   = m::mock('ControlBuilder');

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
			->shouldReceive('layout')->once()->with('orchestra/foundation::components.form')->andReturn(null)
			->shouldReceive('attributes')->once()
				->with(array('url' => handles('orchestra::extensions/configure/foo.bar'), 'method' => 'POST'))
				->andReturn(null)
			->shouldReceive('fieldset')->once()->with(m::type('Closure'))->andReturnUsing(
				function ($c) use ($fieldset)
				{
					$c($fieldset);
				});

		Form::shouldReceive('of')->once()
			->with('orchestra.extension: foo/bar', m::type('Closure'))->andReturnUsing(
				function ($t, $c) use ($form)
				{
					$c($form);
					return 'foo';
				});
		Extension::shouldReceive('option')->once()
			->with('foo/bar', 'handles')->andReturn('foo');
		HTML::shouldReceive('link')->once()
			->with(handles("orchestra/foundation::extensions/update/foo.bar"), m::any(), m::any())->andReturn('foo');
		
		$stub = new ExtensionPresenter;

		$this->assertEquals('foo', $stub->form($model, 'foo/bar'));
	}
}
