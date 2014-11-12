<?php namespace Orchestra\Foundation\Presenter\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Orchestra\Foundation\Presenter\AbstractablePresenter;

class AbstractablePresenterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        Facade::clearResolvedInstances();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Presenter\AbstractablePresenter::handles()
     * method.
     *
     * @test
     */
    public function testHandlesMethod()
    {
        $app = new Container;
        Facade::setFacadeApplication($app);

        $app['orchestra.app'] = $orchestra = m::mock('\Orchestra\Foundation\Application')->makePartial();

        $orchestra->shouldReceive('handles')->with(m::type('String'), m::type('Array'))
                ->andReturnUsing(function ($s) {
                    return "foobar/{$s}";
                });

        $stub = new PresenterStub;
        $this->assertEquals('foobar/hello', $stub->handles('hello'));
    }

    /**
     * Test Orchestra\Foundation\Presenter\AbstractablePresenter::setupForm()
     * method.
     *
     * @test
     */
    public function testSetupFormMethod()
    {
        $form = m::mock('\Orchestra\Html\Form\Grid')->makePartial();

        $form->shouldReceive('layout')->once()
            ->with('orchestra/foundation::components.form')->andReturnNull();

        $stub = new PresenterStub;
        $stub->setupForm($form);
    }
}

class PresenterStub extends AbstractablePresenter
{
    //
}
