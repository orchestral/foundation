<?php namespace Orchestra\Foundation\Http\Controllers\TestCase;

use Mockery as m;
use Illuminate\Support\Fluent;
use Orchestra\Testing\TestCase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ResourcesControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->disableMiddlewareForAllTests();
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->register('Orchestra\Resources\ResourcesServiceProvider');
    }

    /**
     * Bind dependencies.
     *
     * @return array
     */
    protected function bindDependencies()
    {
        $presenter = m::mock('\Orchestra\Foundation\Http\Presenters\Resource');

        App::instance('Orchestra\Foundation\Http\Presenters\Resource', $presenter);

        return $presenter;
    }

    /**
     * Test GET /admin/resources.
     *
     * @test
     */
    public function testGetIndexAction()
    {
        $this->getProcessorMock()->shouldReceive('index')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\ResourcesController'))
            ->andReturnUsing(function ($listener) {
                return $listener->showResourcesList([]);
            });

        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::resources.index', [], [])->andReturn('show.all');

        $this->call('GET', 'admin/resources/index');
        $this->assertResponseOk();
    }

    /**
     * Test GET /admin/resources/laravel.
     *
     * @test
     */
    public function testGetCallAction()
    {
        $this->getProcessorMock()->shouldReceive('show')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\ResourcesController'), 'laravel/index')
            ->andReturnUsing(function ($listener) {
                return $listener->onRequestSucceed([]);
            });

        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::resources.page', [], [])->andReturn('show.request');

        $this->call('GET', 'admin/resources/laravel/index');
        $this->assertResponseOk();
    }

    /**
     * Get processor mock.
     *
     * @return \Orchestra\Foundation\Processor\ResourceLoader
     */
    protected function getProcessorMock()
    {
        $processor = m::mock('\Orchestra\Foundation\Processor\ResourceLoader', [
            m::mock('\Orchestra\Foundation\Http\Presenters\Resource'),
            m::mock('\Orchestra\Resources\Factory'),
        ]);

        $this->app->instance('Orchestra\Foundation\Processor\ResourceLoader', $processor);

        return $processor;
    }

    /**
     * Get request data.
     *
     * @return array
     */
    protected function getData()
    {
        return [
            'laravel' => new Fluent(['visible' => true, 'name' => 'Laravel']),
        ];
    }
}
