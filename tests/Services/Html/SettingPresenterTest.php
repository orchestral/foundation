<?php namespace Orchestra\Foundation\Tests\Services\Html;

use Mockery as m;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Foundation\Services\Html\SettingPresenter;

class SettingPresenterTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Foundation\Services\Html\SettingPresenter::form() 
	 * method.
	 *
	 * @test
	 */
	public function testFormMethod()
	{
		$model    = new \Illuminate\Support\Fluent(array(
			'email_smtp_password' => 123456,
		));
		$form          = m::mock('FormBuilder');
		$siteFieldset  = m::mock('SiteFieldsetBuilder');
		$emailFieldset = m::mock('EmailFieldsetBuilder');
		$siteControl   = m::mock('SiteControlBuilder');
		$emailControl  = m::mock('EmailControlBuilder');

		$siteControl->shouldReceive('label')->times(3)->andReturn(null)
			->shouldReceive('attributes')->twice()->andReturn(null)
			->shouldReceive('options')->once()->andReturn(null);

		$emailControl->shouldReceive('label')->times(9)->andReturn(null)
			->shouldReceive('attributes')->once()->andReturn(null)
			->shouldReceive('options')->twice()->andReturn(null)
			->shouldReceive('help')->once()->with('help-foo');

		$siteFieldset->shouldReceive('control')->times(3)->with(m::any(), m::any(), m::type('Closure'))->andReturnUsing(
			function ($t, $n, $c) use ($siteControl)
			{
				$c($siteControl);
			});
		$emailFieldset->shouldReceive('control')->times(9)->with(m::any(), m::any(), m::type('Closure'))->andReturnUsing(
			function ($t, $n, $c) use ($emailControl)
			{
				$c($emailControl);
			});

		$form->shouldReceive('with')->once()->with($model)->andReturn(null)
			->shouldReceive('layout')->once()->with('orchestra/foundation::components.form')->andReturn(null)
			->shouldReceive('attributes')->once()
				->with(array('url' => handles('orchestra::settings'), 'method' => 'POST'))->andReturn(null)
			->shouldReceive('fieldset')->once()
				->with(trans('orchestra/foundation::label.settings.application'), m::type('Closure'))->andReturnUsing(
					function ($t, $c) use ($siteFieldset)
					{
						$c($siteFieldset);
					})
			->shouldReceive('fieldset')->once()
				->with(trans('orchestra/foundation::label.settings.mail'), m::type('Closure'))->andReturnUsing(
					function ($t, $c) use ($emailFieldset)
					{
						$c($emailFieldset);
					});

		\Orchestra\Support\Facades\Form::shouldReceive('of')->once()
			->with('orchestra.settings', m::type('Closure'))->andReturnUsing(
				function($n, $c) use ($form)
				{
					$c($form);
					return 'foo';
				});
		\Illuminate\Support\Facades\HTML::shouldReceive('create')->once()
			->with('span', '******')->andReturn('span');
		\Illuminate\Support\Facades\HTML::shouldReceive('link')->once()
			->with('#', m::any(), m::type('Array'))->andReturn('link');
		\Illuminate\Support\Facades\Form::shouldReceive('hidden')->once()
			->with('change_password', 'no')->andReturn('hidden');
		\Illuminate\Support\Facades\HTML::shouldReceive('create')->once()
			->with('span', 'raw-help-foo', m::type('Array'))->andReturn('help-foo');
		\Illuminate\Support\Facades\HTML::shouldReceive('raw')->once()
			->with('span&nbsp;&nbsp;linkhidden')->andReturn('raw-help-foo');
		$this->assertEquals('foo', SettingPresenter::form($model));
	}
}
