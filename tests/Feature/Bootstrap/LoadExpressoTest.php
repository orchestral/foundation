<?php

namespace Orchestra\Tests\Feature\Bootstrap;

use Illuminate\Pagination\Paginator;
use Mockery as m;
use Orchestra\Support\Facades\Meta;
use Orchestra\Tests\Feature\TestCase;

class LoadExpressoTest extends TestCase
{
    /** @test */
    public function it_can_register_decorator()
    {
        $compiler = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        $this->assertEquals(
            '<?php echo \app(\'orchestra.decorator\')->render("foo"); ?>', $compiler->compileString('@decorator("foo")')
        );
    }

    /** @test */
    public function it_can_register_html_title()
    {
        $this->instance('orchestra.platform.memory', $memory = m::mock('\Orchestra\Contracts\Memory\Provider'));

        Meta::shouldReceive('get')->once()->with('title', '')->andReturn('')
            ->shouldReceive('get')->once()
                ->with('html::title.format.site', '{site.name} (Page {page.number})')
                ->andReturn('{site.name} (Page {page.number})')
            ->shouldReceive('get')->once()
                ->with('html::title.format.page', '{page.title} &mdash; {site.name}')
                ->andReturn('{page.title} &mdash; {site.name}');

        Paginator::currentPageResolver(function () {
            return 1;
        });

        $memory->shouldReceive('get')->once()->with('site.name', '')->andReturn('Foo');

        $this->assertEquals('<title>Foo</title>', $this->app['html']->title());
    }

    /** @test */
    public function it_can_register_html_title_with_page_number()
    {
        $this->instance('orchestra.platform.memory', $memory = m::mock('\Orchestra\Contracts\Memory\Provider'));

        Meta::shouldReceive('get')->once()->with('title', '')->andReturn('')
            ->shouldReceive('get')->once()
                ->with('html::title.format.site', '{site.name} (Page {page.number})')
                ->andReturn('{site.name} (Page {page.number})')
            ->shouldReceive('get')->once()
                ->with('html::title.format.page', '{page.title} &mdash; {site.name}')
                ->andReturn('{page.title} &mdash; {site.name}');

        Paginator::currentPageResolver(function () {
            return 5;
        });

        $memory->shouldReceive('get')->once()->with('site.name', '')->andReturn('Foo');

        $this->assertEquals('<title>Foo (Page 5)</title>', $this->app['html']->title());
    }

    /** @test */
    public function it_can_register_html_title_with_page_title()
    {
        $this->instance('orchestra.platform.memory', $memory = m::mock('\Orchestra\Contracts\Memory\Provider'));

        Paginator::currentPageResolver(function () {
            return 1;
        });

        Meta::shouldReceive('get')->once()->with('title', '')->andReturn('Foobar')
            ->shouldReceive('get')->once()
                ->with('html::title.format.site', '{site.name} (Page {page.number})')
                ->andReturn('{site.name} (Page {page.number})')
            ->shouldReceive('get')->once()
                ->with('html::title.format.page', '{page.title} &mdash; {site.name}')
                ->andReturn('{page.title} &mdash; {site.name}');

        $memory->shouldReceive('get')->once()
                ->with('site.name', '')->andReturn('Foo');

        $this->assertEquals('<title>Foobar &mdash; Foo</title>', $this->app['html']->title());
    }

    /** @test */
    public function it_can_register_html_title_with_page_title_and_number()
    {
        $this->instance('orchestra.platform.memory', $memory = m::mock('\Orchestra\Contracts\Memory\Provider'));

        Paginator::currentPageResolver(function () {
            return 5;
        });

        Meta::shouldReceive('get')->once()
                ->with('html::title.format.site', '{site.name} (Page {page.number})')
                ->andReturn('{site.name} (Page {page.number})')
            ->shouldReceive('get')->once()
                ->with('html::title.format.page', '{page.title} &mdash; {site.name}')
                ->andReturn('{page.title} &mdash; {site.name}');

        $memory->shouldReceive('get')->once()
            ->with('site.name', '')->andReturn('Foo');

        $this->assertEquals('<title>Foobar &mdash; Foo (Page 5)</title>', $this->app['html']->title('Foobar'));
    }
}
