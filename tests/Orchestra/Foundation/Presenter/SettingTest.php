<?php namespace Orchestra\Foundation\Presenter\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Fluent;
use Orchestra\Foundation\Presenter\Setting;

class SettingTest extends \PHPUnit_Framework_TestCase
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

        $this->app['orchestra.app'] = m::mock('OrchestraApplication');
        $this->app['translator'] = m::mock('Translator');

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
     * Test Orchestra\Foundation\Presenter\Setting::form()
     * method.
     *
     * @test
     */
    public function testFormMethod()
    {
        $app   = $this->app;
        $model = new Fluent(array(
            'email_password' => 123456,
        ));

        $form = m::mock('FormBuilder');

        $siteFieldset = m::mock('SiteFormFieldsetBuilder');
        $siteControl  = m::mock('SiteFormControlBuilder');

        $emailFieldset = m::mock('EmailFormFieldsetBuilder');
        $emailControl  = m::mock('EmailFormControlBuilder');

        $stub = new Setting;

        $siteFieldset->shouldReceive('control')->times(3)
                ->with(m::any(), m::any(), m::type('Closure'))
                ->andReturnUsing(function ($t, $n, $c) use ($siteControl) {
                    $c($siteControl);
                });
        $siteControl->shouldReceive('label')->times(3)->andReturn(null)
            ->shouldReceive('attributes')->twice()->andReturn(null)
            ->shouldReceive('options')->once()->andReturn(null);

        $emailFieldset->shouldReceive('control')->times(9)
                ->with(m::any(), m::any(), m::type('Closure'))
                ->andReturnUsing(function ($t, $n, $c) use ($emailControl) {
                    $c($emailControl);
                });
        $emailControl->shouldReceive('label')->times(9)->andReturn(null)
            ->shouldReceive('attributes')->once()->andReturn(null)
            ->shouldReceive('options')->twice()->andReturn(null)
            ->shouldReceive('help')->once()->with('email.password.help');

        $form->shouldReceive('setup')->once()
                ->with($stub, 'orchestra::settings', $model)->andReturn(null)
            ->shouldReceive('fieldset')->once()
                ->with(trans('orchestra/foundation::label.settings.application'), m::type('Closure'))
                ->andReturnUsing(function ($t, $c) use ($siteFieldset) {
                    $c($siteFieldset);
                })
            ->shouldReceive('fieldset')->once()
                ->with(trans('orchestra/foundation::label.settings.mail'), m::type('Closure'))
                ->andReturnUsing(function ($t, $c) use ($emailFieldset) {
                    $c($emailFieldset);
                });

        $app['orchestra.form'] = m::mock('\Orchestra\Html\Form\Environment')->shouldDeferMissing();
        $app['view'] = m::mock('\Illuminate\View\Environment')->shouldDeferMissing();

        $app['orchestra.form']->shouldReceive('of')->once()
                ->with('orchestra.settings', m::type('Closure'))
                ->andReturnUsing(function ($n, $c) use ($form) {
                    $c($form);
                    return 'foo';
                });
        $app['view']->shouldReceive('make')->once()
            ->with('orchestra/foundation::settings.email-password', compact('model'))
            ->andReturn('email.password.help');

        $this->assertEquals('foo', $stub->form($model));
    }
}
