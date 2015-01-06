<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Orchestra\Testing\TestCase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Extension;
use Orchestra\Support\Facades\Publisher;
use Orchestra\Support\Facades\Foundation;

class ExtensionsControllerTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        View::shouldReceive('addNamespace');
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
        Foundation::shouldReceive('handles')->once()->with('orchestra::extensions', array())->andReturn('extensions');

        $this->call('GET', 'admin/extensions/activate/laravel.framework');
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test GET /admin/extensions/activate/(:name) when extension is already
     * started.
     *
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetActivateActionGivenStartedExtension()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(true);

        $this->call('GET', 'admin/extensions/activate/laravel.framework');
    }

    /**
     * Test GET /admin/extensions/activate/(:name) with migration error.
     *
     * @test
     */
    public function testGetActivateActionGivenMigrationError()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        Extension::shouldReceive('permission')->once()->with('laravel/framework')->andReturn(false);
        Publisher::shouldReceive('queue')->once()->with('laravel/framework')->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::publisher', array())->andReturn('publisher');

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
        Foundation::shouldReceive('handles')->once()->with('orchestra::extensions', array())->andReturn('extensions');

        $this->call('GET', 'admin/extensions/deactivate/laravel.framework');
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test GET /admin/extensions/activate/(:name) when extension is not
     * started.
     *
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetDeactivateActionGivenNotStartedExtension()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);
        Extension::shouldReceive('activated')->once()->with('laravel/framework')->andReturn(false);

        $this->call('GET', 'admin/extensions/deactivate/laravel.framework');
    }

    /**
     * Test GET /admin/extensions/configure/(:name)
     *
     * @test
     */
    public function testGetConfigureAction()
    {
        $memory = m::mock('\Orchestra\Memory\Provider')->makePartial();
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
        Foundation::shouldReceive('memory')->twice()->andReturn($memory);
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
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetConfigureActionGivenStartedExtension()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);

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

        $memory = m::mock('\Orchestra\Memory\Provider')->makePartial();
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
        Foundation::shouldReceive('memory')->once()->andReturn($memory);
        Foundation::shouldReceive('handles')->once()->with('orchestra::extensions', array())->andReturn('extensions');
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();

        $this->call('POST', 'admin/extensions/configure/laravel.framework', $input);
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test POST /admin/extensions/configure/(:name) when extension is not
     * started.
     *
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testPostConfigureActionGivenNotStartedExtension()
    {
        $input = array(
            'handles' => 'foo',
            '_token'  => 'somesessiontoken',
        );

        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);

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
        Foundation::shouldReceive('handles')->once()
            ->with('orchestra::extensions/configure/laravel.framework', array())->andReturn('extensions');

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
        Foundation::shouldReceive('handles')->once()->with('orchestra::extensions', array())->andReturn('extensions');

        $this->call('GET', 'admin/extensions/update/laravel.framework');
        $this->assertRedirectedTo('extensions');
    }

    /**
     * Test GET /admin/extensions/update/(:name) when extension is not
     * started.
     *
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetUpdateActionGivenNotStartedExtension()
    {
        Extension::shouldReceive('started')->once()->with('laravel/framework')->andReturn(false);

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
        Foundation::shouldReceive('handles')->once()->with('orchestra::publisher', array())->andReturn('publisher');

        $this->call('GET', 'admin/extensions/update/laravel.framework');
        $this->assertRedirectedTo('publisher');
    }
}
