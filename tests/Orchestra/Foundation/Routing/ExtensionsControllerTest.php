<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Testing\TestCase;
use Orchestra\Support\Facades\App as Orchestra;
use Orchestra\Support\Facades\Extension;
use Orchestra\Support\Facades\Form;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Publisher;

class ExtensionsControllerTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Bind dependencies.
     *
     * @return array
     */
    protected function bindDependencies()
    {
        $presenter = m::mock('\Orchestra\Foundation\Presenter\Extension');
        $validator = m::mock('\Orchestra\Foundation\Validation\Extension');

        App::instance('Orchestra\Foundation\Presenter\Extension', $presenter);
        App::instance('Orchestra\Foundation\Validation\Extension', $validator);

        return array($presenter, $validator);
    }

    /**
     * Test GET /admin/extensions
     *
     * @test
     */
    public function testGetIndexAction()
    {
        Extension::shouldReceive('detect')->once()->andReturn('foo');
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::extensions.index', array('extensions' => 'foo'))
            ->andReturn('foo');

        $this->call('GET', 'admin/extensions');
        $this->assertResponseOk();
    }

    /**
     * Test GET /admin/extensions/activate/(:name)
     *
     * @test
     */
    public function testGetActivateAction()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        Extension::shouldReceive('permission')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('activate')->once()->with('laravel/framework')->andReturn(true);
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();
        Orchestra::shouldReceive('handles')->once()->with('orchestra::extensions')->andReturn('extensions');

        $this->call('GET', 'admin/extensions/activate/laravel.framework');
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test GET /admin/extensions/activate/(:name) when extension is already
     * started.
     *
     * @test
     */
    public function testGetActivateActionGivenStartedExtension()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        App::shouldReceive('abort')->once()->with(404);

        $this->call('GET', 'admin/extensions/activate/laravel.framework');
    }

    /**
     * Test GET /admin/extensions/activate/(:name) with migration error.
     *
     * @test
     */
    public function testGetActivateActionGivenMgrationError()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        Extension::shouldReceive('permission')->once()->with('laravel/framework')->andReturn(false);
        Publisher::shouldReceive('queue')->once()->with('laravel/framework')->andReturnNull();
        Orchestra::shouldReceive('handles')->once()->with('orchestra::publisher')->andReturn('publisher');

        $this->call('GET', 'admin/extensions/activate/laravel.framework');
        $this->assertRedirectedTo('publisher');
    }

    /**
     * Test GET /admin/extensions/activate/(:name)
     *
     * @test
     */
    public function testGetDeactivateAction()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('deactivate')->once()->with('laravel/framework')->andReturn(true);
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();
        Orchestra::shouldReceive('handles')->once()->with('orchestra::extensions')->andReturn('extensions');

        $this->call('GET', 'admin/extensions/deactivate/laravel.framework');
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test GET /admin/extensions/activate/(:name) when extension is not
     * started.
     *
     * @test
     */
    public function testGetDeactivateActionGivenNotStartedExtension()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        Extension::shouldReceive('activated')->once()->with('laravel/framework')->andReturn(false);
        App::shouldReceive('abort')->once()->with(404);

        $this->call('GET', 'admin/extensions/deactivate/laravel.framework');
    }

    /**
     * Test GET /admin/extensions/configure/(:name)
     *
     * @test
     */
    public function testGetConfigureAction()
    {
        $memory = m::mock('\Orchestra\Memory\Provider[get]');
        list($presenter,) = $this->bindDependencies();

        $memory->shouldReceive('get')->once()
                ->with('extensions.active.laravel/framework.config', array())->andReturn(array())
            ->shouldReceive('get')->once()
                ->with('extension_laravel/framework', array())->andReturn(array())
            ->shouldReceive('get')->once()
                ->with('extensions.available.laravel/framework.name', 'laravel/framework')
                ->andReturn('Laravel Framework');
        $presenter->shouldReceive('configure')->once()->andReturn('edit.extension');

        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        Orchestra::shouldReceive('memory')->twice()->andReturn($memory);
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::extensions.configure', m::type('Array'))->andReturn('foo');

        $this->call('GET', 'admin/extensions/configure/laravel.framework');
        $this->assertResponseOk();
    }

    /**
     * Test GET /admin/extensions/configure/(:name) when extension is already
     * started.
     *
     * @test
     */
    public function testGetConfigureActionGivenStartedExtension()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        App::shouldReceive('abort')->once()->with(404);

        $this->call('GET', 'admin/extensions/configure/laravel.framework');
    }

    /**
     * Test POST /admin/extensions/configure/(:name)
     *
     * @test
     */
    public function testPostConfigureAction()
    {
        $input = array(
            'handles' => 'foo',
            '_token'  => 'somesessiontoken',
        );

        $memory = m::mock('\Orchestra\Memory\Provider[get,put]');
        list(, $validator) = $this->bindDependencies();

        $memory->shouldReceive('get')->once()
                ->with('extension.active.laravel/framework.config', array())->andReturn(array())
            ->shouldReceive('put')->once()
                ->with('extensions.active.laravel/framework.config', array('handles' => 'foo'))->andReturnNull()
            ->shouldReceive('put')->once()
                ->with('extension_laravel/framework', array('handles' => 'foo'))->andReturnNull();

        $validator->shouldReceive('with')->once()
                ->with($input, array("orchestra.validate: extension.laravel/framework"))->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);

        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        Orchestra::shouldReceive('memory')->once()->andReturn($memory);
        Orchestra::shouldReceive('handles')->once()->with('orchestra::extensions')->andReturn('extensions');
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();

        $this->call('POST', 'admin/extensions/configure/laravel.framework', $input);
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test POST /admin/extensions/configure/(:name) when extension is not
     * started.
     *
     * @test
     */
    public function testPostConfigureActionGivenNotStartedExtension()
    {
        $input = array(
            'handles' => 'foo',
            '_token'  => 'somesessiontoken',
        );

        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        App::shouldReceive('abort')->once()->with(404);

        $this->call('POST', 'admin/extensions/configure/laravel.framework', $input);
    }

    /**
     * Test POST /admin/extensions/configure/(:name) with validation error.
     *
     * @test
     */
    public function testPostConfigureActionGivenValidationError()
    {
        $input = array(
            'handles' => 'foo',
            '_token'  => 'somesessiontoken',
        );

        list(, $validator) = $this->bindDependencies();

        $validator->shouldReceive('with')->once()
                ->with($input, array("orchestra.validate: extension.laravel/framework"))->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(true);

        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        Orchestra::shouldReceive('handles')->once()
            ->with('orchestra::extensions/configure/laravel.framework')->andReturn('extensions');

        $this->call('POST', 'admin/extensions/configure/laravel.framework', $input);
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test GET /admin/extensions/update/(:name)
     *
     * @test
     */
    public function testGetUpdateAction()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('permission')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('publish')->once()->with('laravel/framework')->andReturn(true);
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();
        Orchestra::shouldReceive('handles')->once()->with('orchestra::extensions')->andReturn('extensions');

        $this->call('GET', 'admin/extensions/update/laravel.framework');
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test GET /admin/extensions/update/(:name) when extension is not
     * started.
     *
     * @test
     */
    public function testGetUpdateActionGivenNotStartedExtension()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        App::shouldReceive('abort')->once()->with(404);

        $this->call('GET', 'admin/extensions/update/laravel.framework');
    }

    /**
     * Test GET /admin/extensions/update/(:name) with migration error.
     *
     * @test
     */
    public function testGetUpdateActionGivenMgrationError()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);
        Extension::shouldReceive('permission')->once()->with('laravel/framework')->andReturn(false);
        Publisher::shouldReceive('queue')->once()->with('laravel/framework')->andReturnNull();
        Orchestra::shouldReceive('handles')->once()->with('orchestra::publisher')->andReturn('publisher');

        $this->call('GET', 'admin/extensions/update/laravel.framework');
        $this->assertRedirectedTo('publisher');
    }
}
