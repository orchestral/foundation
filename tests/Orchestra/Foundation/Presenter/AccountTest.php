<?php namespace Orchestra\Foundation\Presenter\TestCase;

use Mockery as m;
use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Form;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Foundation\Presenter\Account;

class AccountTest extends TestCase
{
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
        $model    = new Fluent;
        $form     = m::mock('FormBuilder');
        $fieldset = m::mock('FieldsetBuilder');
        $control  = m::mock('ControlBuilder');

        $control->shouldReceive('label')->twice()->andReturn(null);

        $fieldset->shouldReceive('control')->twice()->with('input:text', m::any(), m::type('Closure'))
            ->andReturnUsing(function ($t, $n, $c) use ($control) {
                $c($control);
            });
        $form->shouldReceive('with')->once()->with($model)->andReturn(null)
            ->shouldReceive('layout')->once()->with('orchestra/foundation::components.form')->andReturn(null)
            ->shouldReceive('attributes')->once()->with(array('url' => 'foo', 'method' => 'POST'))->andReturn(null)
            ->shouldReceive('hidden')->once()->with('id')->andReturn(null)
            ->shouldReceive('fieldset')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($fieldset) {
                 $c($fieldset);
                });

        Form::shouldReceive('of')->once()->with('orchestra.account', m::type('Closure'))
            ->andReturnUsing(function ($f, $c) use ($form) {
                $c($form);
                return 'foo';
            });

        $stub = new Account;

        $this->assertEquals('foo', $stub->profileForm($model, 'foo'));
    }

    /**
     * Test Orchestra\Foundation\Presenter\Account::passwordForm()
     * method.
     *
     * @test
     */
    public function testPasswordFormMethod()
    {
        $model    = new Fluent;
        $form     = m::mock('FormBuilder');
        $fieldset = m::mock('FieldsetBuilder');
        $control  = m::mock('ControlBuilder');

        $control->shouldReceive('label')->times(3)->andReturn(null);

        $fieldset->shouldReceive('control')->times(3)->with('input:password', m::any(), m::type('Closure'))
            ->andReturnUsing(function ($t, $n, $c) use ($control) {
                $c($control);
            });
        $form->shouldReceive('with')->once()->with($model)->andReturn(null)
            ->shouldReceive('layout')->once()->with('orchestra/foundation::components.form')->andReturn(null)
            ->shouldReceive('attributes')->once()
                ->with(array('url' => handles('orchestra::account/password'), 'method' => 'POST'))->andReturn(null)
            ->shouldReceive('hidden')->once()->with('id')->andReturn(null)
            ->shouldReceive('fieldset')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($fieldset) {
                    $c($fieldset);
                });

        Form::shouldReceive('of')->once()->with('orchestra.account: password', m::type('Closure'))
            ->andReturnUsing(function ($f, $c) use ($form) {
                $c($form);
                return 'foo';
            });

        $stub = new Account;

        $this->assertEquals('foo', $stub->passwordForm($model, 'foo'));
    }
}
