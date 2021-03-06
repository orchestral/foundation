<?php

namespace Orchestra\Tests\Unit\Http\Presenters;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Fluent;
use Mockery as m;
use Orchestra\Foundation\Http\Presenters\Extension;
use PHPUnit\Framework\TestCase;

class ExtensionTest extends TestCase
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
    protected function setUp(): void
    {
        $this->app = new Container();

        $this->app['orchestra.app'] = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $this->app['translator'] = m::mock('\Illuminate\Translation\Translator')->makePartial();

        $this->app['orchestra.app']->shouldReceive('handles');
        $this->app['translator']->shouldReceive('get');

        Facade::clearResolvedInstances();
        Container::setInstance($this->app);
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        unset($this->app);

        m::close();
    }

    /**
     * Test Orchestra\Foundation\Http\Presenters\Extension::form()
     * method.
     *
     * @test
     */
    public function testFormMethod()
    {
        $model = new Fluent();
        $app = $this->app;
        $app['html'] = m::mock('\Orchestra\Html\HtmlBuilder')->makePartial();

        $form = m::mock('\Orchestra\Contracts\Html\Form\Factory');
        $extension = m::mock('\Orchestra\Contracts\Extension\Factory');

        $builder = m::mock('\Orchestra\Contracts\Html\Form\Builder');
        $grid = m::mock('\Orchestra\Contracts\Html\Form\Grid');
        $fieldset = m::mock('\Orchestra\Contracts\Html\Form\Fieldset');
        $control = m::mock('\Orchestra\Contracts\Html\Form\Control');

        $stub = new Extension($extension, $form);

        $control->shouldReceive('label')->once()->andReturnSelf()
            ->shouldReceive('value')->once()->andReturnSelf();
        $fieldset->shouldReceive('control')->once()->with('input:text', m::any())->andReturn($control);
        $grid->shouldReceive('setup')->once()->with($stub, 'orchestra::extensions/foo/bar/configure', $model)->andReturnNull()
            ->shouldReceive('fieldset')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($fieldset) {
                    $c($fieldset);

                    return $fieldset;
                });
        $extension->shouldReceive('option')->once()->with('foo/bar', 'handles')->andReturn('foo');
        $form->shouldReceive('of')->once()
                ->with('orchestra.extension: foo/bar', m::type('Closure'))
                ->andReturnUsing(function ($t, $c) use ($builder, $grid) {
                    $c($grid);

                    return $builder;
                });

        $this->assertSame($builder, $stub->configure($model, 'foo/bar'));
    }
}
