<?php namespace Orchestra\Foundation\Bootstrap\TestCase;

use Illuminate\Pagination\Paginator;
use Mockery as m;
use Orchestra\Testing\TestCase;
use Orchestra\Support\Facades\Meta;

class LoadExpressoTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->make('Orchestra\Foundation\Bootstrap\LoadExpresso')->bootstrap($app);
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /**
     * Test Blade::extend() is registered.
     *
     * @test
     */
    public function testBladeExtendIsRegistered()
    {
        $compiler = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        $this->assertEquals('<?php echo app("orchestra.decorator")->render("foo"); ?>', $compiler->compileString('@decorator("foo")'));
    }

    /**
     * Test HTML::title() macro.
     *
     * @test
     */
    public function testHtmlTitleMacro()
    {
        $this->app['orchestra.platform.memory'] = $memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        Meta::shouldReceive('get')->once()->with('title', '')->andReturn('');

        Paginator::currentPageResolver(function () {
            return 1;
        });

        $memory->shouldReceive('get')->once()->with('site.name', '')->andReturn('Foo');

        $this->assertEquals('<title>Foo</title>', $this->app['html']->title());
    }

    /**
     * Test HTML::title() macro.
     *
     * @test
     */
    public function testHtmlTitleMacroWithPageNumber()
    {
        $this->app['orchestra.platform.memory'] = $memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        Meta::shouldReceive('get')->once()->with('title', '')->andReturn('');

        Paginator::currentPageResolver(function () {
            return 5;
        });

        $memory->shouldReceive('get')->once()
                ->with('site.name', '')->andReturn('Foo')
            ->shouldReceive('get')->once()
                ->with('site.format.title.site', '{site.name} (Page {page.number})')
                ->andReturn('{site.name} (Page {page.number})');

        $this->assertEquals('<title>Foo (Page 5)</title>', $this->app['html']->title());
    }


    /**
     * Test HTML::title() macro with page title.
     *
     * @test
     */
    public function testHtmlTitleMacroWithPageTitle()
    {
        $this->app['orchestra.platform.memory'] = $memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        Paginator::currentPageResolver(function () {
            return 1;
        });

        Meta::shouldReceive('get')->once()->with('title', '')->andReturn('Foobar');

        $memory->shouldReceive('get')->once()
                ->with('site.name', '')->andReturn('Foo')
            ->shouldReceive('get')->once()
                ->with('site.format.title.page', '{page.title} &mdash; {site.name}')
                ->andReturn('{page.title} &mdash; {site.name}');

        $this->assertEquals('<title>Foobar &mdash; Foo</title>', $this->app['html']->title());
    }

    /**
     * Test HTML::title() macro with page title
     * and number.
     *
     * @test
     */
    public function testHtmlTitleMacroWithPageTitleAndNumber()
    {
        $this->app['orchestra.platform.memory'] = $memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        Paginator::currentPageResolver(function () {
            return 5;
        });

        $memory->shouldReceive('get')->once()
            ->with('site.name', '')->andReturn('Foo')
            ->shouldReceive('get')->once()
            ->with('site.format.title.site', '{site.name} (Page {page.number})')
            ->andReturn('{site.name} (Page {page.number})')
            ->shouldReceive('get')->once()
            ->with('site.format.title.page', '{page.title} &mdash; {site.name}')
            ->andReturn('{page.title} &mdash; {site.name}');

        $this->assertEquals('<title>Foobar &mdash; Foo (Page 5)</title>', $this->app['html']->title('Foobar'));
    }

    /**
     * Test Orchestra\Decorator navbar is registered.
     *
     * @test
     */
    public function testDecoratorIsRegistered()
    {
        $stub = $this->app['orchestra.decorator'];
        $view = $stub->render('navbar', []);

        $this->assertInstanceOf('\Orchestra\View\Decorator', $stub);
        $this->assertInstanceOf('\Illuminate\View\View', $view);
        $this->assertEquals('orchestra/foundation::components.navbar', $view->getName());
    }
}
