<?php namespace Orchestra\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\HTML;
use Orchestra\Foundation\Testing\TestCase;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Site;

class MacrosTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test HTML::title() macro.
     *
     * @test
     */
    public function testHtmlTitleMacro()
    {
        App::shouldReceive('memory')->twice()
            ->andReturn($memory = m::mock('Memory'));

        $memory->shouldReceive('get')->once()->with('site.name', '')->andReturn('Foo')
            ->shouldReceive('get')->once()->with('site.format.title', ':pageTitle &mdash; :siteTitle')
            ->andReturn(':pageTitle &mdash; :siteTitle');

        $this->assertEquals('<title>Foo</title>', HTML::title());
    }

    /**
     * Test HTML::title() macro with page title.
     *
     * @test
     */
    public function testHtmlTitleMacroWithPageTitle()
    {
        App::shouldReceive('memory')->twice()
            ->andReturn($memory = m::mock('Memory'));
        Site::shouldReceive('get')->once()
            ->with('title', '')->andReturn('Foobar');

        $memory->shouldReceive('get')->once()->with('site.name', '')->andReturn('Foo')
            ->shouldReceive('get')->once()->with('site.format.title', ':pageTitle &mdash; :siteTitle')
            ->andReturn(':pageTitle &mdash; :siteTitle');

        $this->assertEquals('<title>Foobar &mdash; Foo</title>', HTML::title());
    }

    /**
     * Test Orchestra\Decorator navbar is registered.
     *
     * @test
     */
    public function testDecoratorIsRegistered()
    {
        $stub = App::make('orchestra.decorator');
        $view = $stub->render('navbar', array());

        $this->assertInstanceOf('\Orchestra\View\Decorator', $stub);
        $this->assertInstanceOf('\Illuminate\View\View', $view);
        $this->assertEquals('orchestra/foundation::components.navbar', $view->getName());
    }
}
