<?php namespace Orchestra\Foundation\Presenter\TestCase;

use Mockery as m;
use Illuminate\Support\Fluent;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Orchestra\Foundation\Presenter\Extension;

class ExtensionTest extends \PHPUnit_Framework_TestCase
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

        $this->app['orchestra.app'] = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $this->app['translator'] = m::mock('\Illuminate\Translation\Translator')->makePartial();

        $this->app['orchestra.app']->shouldReceive('handles');
        $this->app['translator']->shouldReceive('trans');

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this->app);
        Container::setInstance($this->app);
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
     * Test Orchestra\Foundation\Presenter\Extension::form()
     * method.
     *
     * @test
     */
    public function testFormMethod()
    {
        $model = new Fluent;
        $app   = $this->app;
        $app['html'] = m::mock('\Orchestra\Html\HtmlBuilder')->makePartial();

        $form = m::mock('\Orchestra\Contracts\Html\Form\Factory');
        $extension = m::mock('\Orchestra\Contracts\Extension\Factory');

        $grid     = m::mock('\Orchestra\Contracts\Html\Form\Grid');
        $fieldset = m::mock('\Orchestra\Contracts\Html\Form\Fieldset');
        $control  = m::mock('\Orchestra\Contracts\Html\Form\Control');

        $stub = new Extension($extension, $form);

        $control->shouldReceive('label')->twice()->andReturnSelf()
            ->shouldReceive('value')->once()->andReturnSelf()
            ->shouldReceive('field')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) {
                    $c();
                });
        $fieldset->shouldReceive('control')->twice()->with('input:text', m::any())->andReturn($control);
        $grid->shouldReceive('setup')->once()->with($stub, 'orchestra::extensions/configure/foo.bar', $model)->andReturnNull()
            ->shouldReceive('fieldset')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($fieldset) {
                    $c($fieldset);
                });
        $extension->shouldReceive('option')->once()->with('foo/bar', 'handles')->andReturn('foo');
        $form->shouldReceive('of')->once()
                ->with('orchestra.extension: foo/bar', m::type('Closure'))
                ->andReturnUsing(function ($t, $c) use ($grid) {
                    $c($grid);
                    return 'foo';
                });

        $app['html']->shouldReceive('link')->once()
                ->with(handles("orchestra/foundation::extensions/update/foo.bar"), m::any(), m::any())
                ->andReturn('foo');

        $this->assertEquals('foo', $stub->configure($model, 'foo/bar'));
    }
}
