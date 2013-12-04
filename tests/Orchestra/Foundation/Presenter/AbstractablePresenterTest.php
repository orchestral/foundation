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

        $app['orchestra.app'] = m::mock('\Orchestra\Foundation\Application')->shouldDeferMissing();
        $app['orchestra.app']->shouldReceive('handles')->with(m::type('String'))
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
        $form = m::mock('FormGrid');
        $form->shouldReceive('layout')->once()
            ->with('orchestra/foundation::components.form')->andReturn(null);

        $stub = new PresenterStub;
        $stub->setupForm($form);
    }
}

class PresenterStub extends AbstractablePresenter
{
    //
}
