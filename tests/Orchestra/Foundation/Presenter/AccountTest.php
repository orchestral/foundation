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
     * Test Orchestra\Foundation\Presenter\Account::profileForm()
     * method.
     *
     * @test
     */
    public function testProfileFormMethod()
    {
        $app      = $this->app;
        $model    = new Fluent;
        $grid     = m::mock('\Orchestra\Html\Form\Grid')->makePartial();
        $fieldset = m::mock('\Orchestra\Html\Form\Fieldset')->makePartial();
        $control  = m::mock('\Orchestra\Html\Form\Control')->makePartial();

        $stub = new Account;

        $control->shouldReceive('label')->twice()->andReturnNull();
        $fieldset->shouldReceive('control')->twice()
                ->with('input:text', m::any(), m::type('Closure'))
                ->andReturnUsing(function ($t, $n, $c) use ($control) {
                    $c($control);
                });
        $grid->shouldReceive('setup')->once()->with($stub, 'foo', $model)->andReturnNull()
            ->shouldReceive('hidden')->once()->with('id')->andReturnNull()
            ->shouldReceive('fieldset')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($fieldset) {
                    $c($fieldset);
                });

        $app['orchestra.form'] = m::mock('\Orchestra\Html\Form\Environment')->makePartial();

        $app['orchestra.form']->shouldReceive('of')->once()
                ->with('orchestra.account', m::type('Closure'))
                ->andReturnUsing(function ($f, $c) use ($grid) {
                    $c($grid);
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
        $grid     = m::mock('\Orchestra\Html\Form\Grid')->makePartial();
        $fieldset = m::mock('\Orchestra\Html\Form\Fieldset')->makePartial();
        $control  = m::mock('\Orchestra\Html\Form\Control')->makePartial();

        $stub = new Account;

        $control->shouldReceive('label')->times(3)->andReturnNull();
        $fieldset->shouldReceive('control')->times(3)
                ->with('input:password', m::any(), m::type('Closure'))
                ->andReturnUsing(function ($t, $n, $c) use ($control) {
                    $c($control);
                });
        $grid->shouldReceive('setup')->once()
                ->with($stub, 'orchestra::account/password', $model)->andReturnNull()
            ->shouldReceive('hidden')->once()->with('id')->andReturnNull()
            ->shouldReceive('fieldset')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($fieldset) {
                    $c($fieldset);
                });

        $app['orchestra.form'] = m::mock('\Orchestra\Html\Form\Environment')->makePartial();

        $app['orchestra.form']->shouldReceive('of')->once()
                ->with('orchestra.account: password', m::type('Closure'))
                ->andReturnUsing(function ($f, $c) use ($grid) {
                    $c($grid);
                    return 'foo';
                });

        $this->assertEquals('foo', $stub->password($model, 'foo'));
    }
}
