<?php namespace Orchestra\Foundation\Presenter\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Fluent;
use Orchestra\Foundation\Presenter\Account;

class AccountTest extends \PHPUnit_Framework_TestCase
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

        $this->app['orchestra.app'] = m::mock('\Orchestra\Foundation\Application')->shouldDeferMissing();
        $this->app['translator'] = m::mock('\Illuminate\Translation\Translator')->shouldDeferMissing();

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
     * Test Orchestra\Foundation\Presenter\Account::profileForm()
     * method.
     *
     * @test
     */
    public function testProfileFormMethod()
    {
        $app      = $this->app;
        $model    = new Fluent;
        $form     = m::mock('FormBuilder');
        $fieldset = m::mock('FormFieldsetBuilder');
        $control  = m::mock('FormControlBuilder');

        $stub = new Account;

        $control->shouldReceive('label')->twice()->andReturn(null);
        $fieldset->shouldReceive('control')->twice()
                ->with('input:text', m::any(), m::type('Closure'))
                ->andReturnUsing(function ($t, $n, $c) use ($control) {
                    $c($control);
                });
        $form->shouldReceive('simple')->once()->with($stub, 'foo', $model)->andReturn(null)
            ->shouldReceive('hidden')->once()->with('id')->andReturn(null)
            ->shouldReceive('fieldset')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($fieldset) {
                    $c($fieldset);
                });

        $app['orchestra.form'] = m::mock('\Orchestra\Html\Form\Environment')->shouldDeferMissing();

        $app['orchestra.form']->shouldReceive('of')->once()
                ->with('orchestra.account', m::type('Closure'))
                ->andReturnUsing(function ($f, $c) use ($form) {
                    $c($form);
                    return 'foo';
                });

        $this->assertEquals('foo', $stub->profile($model, 'foo'));
    }

    /**
     * Test Orchestra\Foundation\Presenter\Account::passwordForm()
     * method.
     *
     * @test
     */
    public function testPasswordFormMethod()
    {
        $app      = $this->app;
        $model    = new Fluent;
        $form     = m::mock('FormBuilder');
        $fieldset = m::mock('FieldsetBuilder');
        $control  = m::mock('ControlBuilder');

        $stub = new Account;

        $control->shouldReceive('label')->times(3)->andReturn(null);
        $fieldset->shouldReceive('control')->times(3)
                ->with('input:password', m::any(), m::type('Closure'))
                ->andReturnUsing(function ($t, $n, $c) use ($control) {
                    $c($control);
                });
        $form->shouldReceive('simple')->once()
                ->with($stub, 'orchestra::account/password', $model)->andReturn(null)
            ->shouldReceive('hidden')->once()->with('id')->andReturn(null)
            ->shouldReceive('fieldset')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($fieldset) {
                    $c($fieldset);
                });

        $app['orchestra.form'] = m::mock('\Orchestra\Html\Form\Environment')->shouldDeferMissing();

        $app['orchestra.form']->shouldReceive('of')->once()
                ->with('orchestra.account: password', m::type('Closure'))
                ->andReturnUsing(function ($f, $c) use ($form) {
                    $c($form);
                    return 'foo';
                });

        $this->assertEquals('foo', $stub->password($model, 'foo'));
    }
}
