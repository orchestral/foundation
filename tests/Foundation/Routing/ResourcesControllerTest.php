<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Fluent;
use Orchestra\Foundation\Testing\TestCase;
use Orchestra\Support\Facades\App as Orchestra;
use Orchestra\Support\Facades\Resources;
use Orchestra\Support\Facades\Table;

class ResourcesControllerTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        View::shouldReceive('share')->once()->with('errors', m::any());
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
     * Bind dependencies.
     *
     * @return array
     */
    protected function bindDependencies()
    {
        $presenter = m::mock('\Orchestra\Foundation\Presenter\Resource');

        App::instance('Orchestra\Foundation\Presenter\Resource', $presenter);

        return $presenter;
    }

    /**
     * Test GET /admin/resources
     *
     * @test
     */
    public function testGetIndexAction()
    {
        $resources = array(
            'foo' => new Fluent(array(
                'visible' => true,
                'name'    => 'Foo',
            )),
        );

        $presenter = $this->bindDependencies();
        $presenter->shouldReceive('table')->once()->andReturn('list.resources');

        Resources::shouldReceive('all')->once()->andReturn($resources);
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::resources.index', m::type('Array'))->andReturn('foo');

        $this->call('GET', 'admin/resources/index');
        $this->assertResponseOk();
    }

    /**
     * Test GET /admin/resources/laravel
     *
     * @test
     */
    public function testGetCallAction()
    {
        $resources = array(
            'laravel' => new Fluent(array(
                'visible' => true,
                'name'    => 'Laravel',
            )),
        );

        Resources::shouldReceive('all')->once()->andReturn($resources);
        Resources::shouldReceive('call')->once()->with('laravel', array('index'))->andReturn('laravel');
        Resources::shouldReceive('response')->once()->with('laravel', m::type('Closure'))
            ->andReturnUsing(function ($n, $c) {
                return $c($n);
            });
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::resources.page', m::type('Array'))->andReturn('foo');

        $this->call('GET', 'admin/resources/laravel/index');
        $this->assertResponseOk();
    }
}
