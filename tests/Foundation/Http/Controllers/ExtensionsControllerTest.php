<?php

namespace Orchestra\Foundation\TestCase\Http\Controllers;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Extension;
use Orchestra\Support\Facades\Publisher;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Testing\BrowserKit\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ExtensionsControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->disableMiddlewareForAllTests();
    }

    /**
     * Bind dependencies.
     *
     * @return array
     */
    protected function bindDependencies()
    {
        $presenter = m::mock('\Orchestra\Foundation\Http\Presenters\Extension');
        $validator = m::mock('\Orchestra\Foundation\Validation\Extension');

        App::instance('Orchestra\Foundation\Http\Presenters\Extension', $presenter);
        App::instance('Orchestra\Foundation\Validation\Extension', $validator);

        return [$presenter, $validator];
    }

    /**
     * Test GET /admin/extensions.
     *
     * @test
     */
    public function testGetIndexAction()
    {
        Extension::shouldReceive('detect')->once()->andReturn('foo');
        Extension::shouldReceive('finish')->once()->andReturnNull();
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::extensions.index', ['extensions' => 'foo'], [])
            ->andReturn('foo');

        $this->call('GET', 'admin/extensions');
        $this->assertResponseOk();
    }

    /**
     * Test POST /admin/extensions/(:name)/activate.
     *
     * @test
     */
    public function testPostActivateAction()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        Extension::shouldReceive('permission')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('activate')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('finish')->once()->andReturnNull();
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::extensions', [])->andReturn('extensions');

        $this->call('POST', 'admin/extensions/laravel/framework/activate');
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test POST /admin/extensions/(:name)/activate when extension is already
     * started.
     *
     * @test
     */
    public function testPostActivateActionGivenStartedExtension()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('finish')->once()->andReturnNull();

        $this->call('POST', 'admin/extensions/laravel/framework/activate');
        $this->assertResponseStatus(404);
    }

    /**
     * Test POST /admin/extensions/(:name)/activate with migration error.
     *
     * @test
     */
    public function testPostActivateActionGivenMigrationError()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        Extension::shouldReceive('permission')->once()->with('laravel/framework')->andReturn(false);
        Extension::shouldReceive('finish')->once()->andReturnNull();
        Publisher::shouldReceive('queue')->once()->with('laravel/framework')->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::publisher', [])->andReturn('publisher');

        $this->call('POST', 'admin/extensions/laravel/framework/activate');
        $this->assertRedirectedTo('publisher');
    }

    /**
     * Test POST /admin/extensions/(:name)/deactivate.
     *
     * @test
     */
    public function testPostDeactivateAction()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('deactivate')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('finish')->once()->andReturnNull();
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::extensions', [])->andReturn('extensions');

        $this->call('POST', 'admin/extensions/laravel/framework/deactivate');
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test POST /admin/extensions/(:name)/deactivate when extension is not
     * started.
     *
     * @test
     */
    public function testPostDeactivateActionGivenNotStartedExtension()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        Extension::shouldReceive('finish')->once()->andReturnNull();
        Extension::shouldReceive('activated')->once()->with('laravel/framework')->andReturn(false);

        $this->call('POST', 'admin/extensions/laravel/framework/deactivate');
        $this->assertResponseStatus(404);
    }

    /**
     * Test GET /admin/extensions/(:name)/configure.
     *
     * @test
     */
    public function testGetConfigureAction()
    {
        $memory = m::mock('\Orchestra\Contracts\Memory\Provider');
        list($presenter, ) = $this->bindDependencies();

        $memory->shouldReceive('get')->once()
                ->with('extensions.active.laravel/framework.config', [])->andReturn([])
            ->shouldReceive('get')->once()
                ->with('extension_laravel/framework', [])->andReturn([])
            ->shouldReceive('get')->once()
                ->with('extensions.available.laravel/framework.name', 'laravel/framework')
                ->andReturn('Laravel Framework');
        $presenter->shouldReceive('configure')->once()->andReturn('edit.extension');

        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('finish')->once()->andReturnNull();
        Foundation::shouldReceive('memory')->twice()->andReturn($memory);
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::extensions.configure', m::type('Array'), [])
            ->andReturn('foo');

        $this->call('GET', 'admin/extensions/laravel/framework/configure');
        $this->assertResponseOk();
    }

    /**
     * Test GET /admin/extensions/(:name)/configure when extension is already
     * started.
     *
     * @test
     */
    public function testGetConfigureActionGivenStartedExtension()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        Extension::shouldReceive('finish')->once()->andReturnNull();

        $this->call('GET', 'admin/extensions/laravel/framework/configure');
        $this->assertResponseStatus(404);
    }

    /**
     * Test POST /admin/extensions/(:name)/configure.
     *
     * @test
     */
    public function testPostConfigureAction()
    {
        $input = [
            'handles' => 'foo',
            '_token' => 'somesessiontoken',
        ];

        $memory = m::mock('\Orchestra\Contracts\Memory\Provider');
        list(, $validator) = $this->bindDependencies();

        $memory->shouldReceive('get')->once()
                ->with('extension.active.laravel/framework.config', [])->andReturn([])
            ->shouldReceive('put')->once()
                ->with('extensions.active.laravel/framework.config', ['handles' => 'foo'])->andReturnNull()
            ->shouldReceive('put')->once()
                ->with('extension_laravel/framework', ['handles' => 'foo'])->andReturnNull();

        $validator->shouldReceive('with')->once()
                ->with($input, ["orchestra.validate: extension.laravel/framework"])->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);

        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('finish')->once()->andReturnNull();
        Foundation::shouldReceive('memory')->once()->andReturn($memory);
        Foundation::shouldReceive('handles')->once()->with('orchestra::extensions', [])->andReturn('extensions');
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();

        $this->call('POST', 'admin/extensions/laravel/framework/configure', $input);
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test POST /admin/extensions/(:name)/configure when extension is not
     * started.
     *
     * @test
     */
    public function testPostConfigureActionGivenNotStartedExtension()
    {
        $input = [
            'handles' => 'foo',
            '_token' => 'somesessiontoken',
        ];

        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        Extension::shouldReceive('finish')->once()->andReturnNull();

        $this->call('POST', 'admin/extensions/laravel/framework/configure', $input);
        $this->assertResponseStatus(404);
    }

    /**
     * Test POST /admin/extensions/(:name)/configure with validation error.
     *
     * @test
     */
    public function testPostConfigureActionGivenValidationError()
    {
        $input = [
            'handles' => 'foo',
            '_token' => 'somesessiontoken',
        ];

        list(, $validator) = $this->bindDependencies();

        $validator->shouldReceive('with')->once()
                ->with($input, ["orchestra.validate: extension.laravel/framework"])->andReturn($validator)
            ->shouldReceive('getMessageBag')->once()->andReturn([])
            ->shouldReceive('fails')->once()->andReturn(true);

        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('finish')->once()->andReturnNull();
        Foundation::shouldReceive('handles')->once()
            ->with('orchestra::extensions/laravel/framework/configure', [])->andReturn('extensions');

        $this->call('POST', 'admin/extensions/laravel/framework/configure', $input);
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test POST /admin/extensions/(:name)/update.
     *
     * @test
     */
    public function testPostUpdateAction()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('permission')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('publish')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('finish')->once()->andReturnNull();
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::extensions', [])->andReturn('extensions');

        $this->call('POST', 'admin/extensions/laravel/framework/update');
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test POST /admin/extensions/(:name)/update when extension is not
     * started.
     *
     * @test
     */
    public function testPostUpdateActionGivenNotStartedExtension()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        Extension::shouldReceive('finish')->once()->andReturnNull();

        $this->call('POST', 'admin/extensions/laravel/framework/update');
        $this->assertResponseStatus(404);
    }

    /**
     * Test POST /admin/extensions/(:name)/update with migration error.
     *
     * @test
     */
    public function testPostUpdateActionGivenMgrationError()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('permission')->once()->with('laravel/framework')->andReturn(false);
        Extension::shouldReceive('finish')->once()->andReturnNull();
        Publisher::shouldReceive('queue')->once()->with('laravel/framework')->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::publisher', [])->andReturn('publisher');

        $this->call('POST', 'admin/extensions/laravel/framework/update');
        $this->assertRedirectedTo('publisher');
    }
}
