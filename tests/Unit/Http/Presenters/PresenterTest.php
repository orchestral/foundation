<?php

namespace Orchestra\Tests\Unit\Http\Presenters;

use Mockery as m;
use Orchestra\Testing\TestCase;
use Orchestra\Foundation\RouteResolver;
use Orchestra\Foundation\Http\Presenters\Presenter;

class PresenterTest extends TestCase
{
    /**
     * Test Orchestra\Foundation\Http\Presenters\Presenter::handles()
     * method.
     *
     * @test
     */
    public function testHandlesMethod()
    {
        $orchestra = m::mock('\Orchestra\Foundation\Foundation[handles]', [
            $this->app, new RouteResolver($this->app),
        ]);

        $this->app->instance('orchestra.app', $orchestra);

        $orchestra->shouldReceive('handles')->with(m::type('String'), m::type('Array'))
            ->andReturnUsing(function ($s) {
                return "foobar/{$s}";
            });

        $stub = new PresenterStub();
        $this->assertEquals('foobar/hello', $stub->handles('hello'));
    }

    /**
     * Test Orchestra\Foundation\Http\Presenters\Presenter::setupForm()
     * method.
     *
     * @test
     */
    public function testSetupFormMethod()
    {
        $form = m::mock('\Orchestra\Contracts\Html\Form\Grid');

        $form->shouldReceive('layout')->once()
            ->with('orchestra/foundation::components.form')->andReturnNull();

        $stub = new PresenterStub();
        $this->assertNull($stub->setupForm($form));
    }
}

class PresenterStub extends Presenter
{
    //
}
