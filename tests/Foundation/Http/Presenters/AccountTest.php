<?php

namespace Orchestra\Foundation\TestCase\Http\Presenters;

use Mockery as m;
use Illuminate\Support\Fluent;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Orchestra\Foundation\Http\Presenters\Account;

class AccountTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        $app = new Container();

        $app['orchestra.app'] = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $app['translator'] = m::mock('\Illuminate\Translation\Translator')->makePartial();

        $app['orchestra.app']->shouldReceive('handles');
        $app['translator']->shouldReceive('trans');

        Facade::clearResolvedInstances();
        Container::setInstance($app);
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Https\Presenters\Account::profileForm()
     * method.
     *
     * @test
     */
    public function testProfileFormMethod()
    {
        $form = m::mock('\Orchestra\Contracts\Html\Form\Factory');

        $builder = m::mock('\Orchestra\Contracts\Html\Form\Builder');
        $grid = m::mock('\Orchestra\Contracts\Html\Form\Grid');
        $fieldset = m::mock('\Orchestra\Contracts\Html\Form\Fieldset');
        $control = m::mock('\Orchestra\Contracts\Html\Form\Control');

        $model = new Fluent();
        $stub = new Account($form);

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
                ->andReturnUsing(function ($f, $c) use ($builder, $grid) {
                    $c($grid);

                    return $builder;
                });

        $this->assertSame($builder, $stub->profile($model, 'foo'));
    }

    /**
     * Test Orchestra\Foundation\Https\Presenters\Account::passwordForm()
     * method.
     *
     * @test
     */
    public function testPasswordFormMethod()
    {
        $builder = m::mock('\Orchestra\Contracts\Html\Form\Builder');
        $grid = m::mock('\Orchestra\Contracts\Html\Form\Grid');
        $fieldset = m::mock('\Orchestra\Contracts\Html\Form\Fieldset');
        $control = m::mock('\Orchestra\Contracts\Html\Form\Control');
        $form = m::mock('\Orchestra\Contracts\Html\Form\Factory');

        $model = new Fluent();
        $stub = new Account($form);

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
                ->andReturnUsing(function ($f, $c) use ($builder, $grid) {
                    $c($grid);

                    return $builder;
                });

        $this->assertSame($builder, $stub->password($model, 'foo'));
    }
}
