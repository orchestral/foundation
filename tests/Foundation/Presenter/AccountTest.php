<?php namespace Orchestra\Foundation\Presenter\TestCase;

use Mockery as m;
use Illuminate\Support\Fluent;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Orchestra\Foundation\Presenter\Account;

class AccountTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $app = new Container;

        $app['orchestra.app'] = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $app['translator'] = m::mock('\Illuminate\Translation\Translator')->makePartial();

        $app['orchestra.app']->shouldReceive('handles');
        $app['translator']->shouldReceive('trans');

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
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
        $form = m::mock('\Orchestra\Contracts\Html\Form\Factory');

        $grid     = m::mock('\Orchestra\Contracts\Html\Form\Grid');
        $fieldset = m::mock('\Orchestra\Contracts\Html\Form\Fieldset');
        $control  = m::mock('\Orchestra\Contracts\Html\Form\Control');

        $model = new Fluent;
        $stub  = new Account($form);

        $control->shouldReceive('label')->twice()->andReturnSelf();
        $fieldset->shouldReceive('control')->twice()->with('input:text', m::any())->andReturn($control);
        $grid->shouldReceive('setup')->once()->with($stub, 'foo', $model)->andReturnNull()
            ->shouldReceive('hidden')->once()->with('id')->andReturnNull()
            ->shouldReceive('fieldset')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($fieldset) {
                    $c($fieldset);
                });
        $form->shouldReceive('of')->once()
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
        $grid     = m::mock('\Orchestra\Contracts\Html\Form\Grid');
        $fieldset = m::mock('\Orchestra\Contracts\Html\Form\Fieldset');
        $control  = m::mock('\Orchestra\Contracts\Html\Form\Control');
        $form     = m::mock('\Orchestra\Contracts\Html\Form\Factory');


        $model = new Fluent;
        $stub  = new Account($form);

        $control->shouldReceive('label')->times(3)->andReturnSelf();
        $fieldset->shouldReceive('control')->times(3)->with('input:password', m::any())->andReturn($control);
        $grid->shouldReceive('setup')->once()->with($stub, 'orchestra::account/password', $model)->andReturnNull()
            ->shouldReceive('hidden')->once()->with('id')->andReturnNull()
            ->shouldReceive('fieldset')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($fieldset) {
                    $c($fieldset);
                });
        $form->shouldReceive('of')->once()
                ->with('orchestra.account: password', m::type('Closure'))
                ->andReturnUsing(function ($f, $c) use ($grid) {
                    $c($grid);
                    return 'foo';
                });

        $this->assertEquals('foo', $stub->password($model, 'foo'));
    }
}
