<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Orchestra\Support\Facades\Form;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Foundation\Testing\TestCase;

class SettingsControllerTest extends TestCase
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
        $presenter = m::mock('\Orchestra\Foundation\Presenter\Setting');
        $validator = m::mock('\Orchestra\Foundation\Validation\Setting');

        App::instance('Orchestra\Foundation\Presenter\Setting', $presenter);
        App::instance('Orchestra\Foundation\Validation\Setting', $validator);

        return array($presenter, $validator);
    }

    /**
     * Test GET /admin/settings
     *
     * @test
     */
    public function testGetIndexAction()
    {
        $memory = m::mock('\Orchestra\Memory\Provider')->makePartial();
        list($presenter,) = $this->bindDependencies();

        $memory->shouldReceive('get')->times(14)->andReturn('');
        $presenter->shouldReceive('form')->once()->andReturn('edit.settings');

        Foundation::shouldReceive('memory')->once()->andReturn($memory);
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::settings.index', m::type('Array'))->andReturn('foo');

        $this->call('GET', 'admin/settings');
        $this->assertResponseOk();
    }

    /**
     * Test POST /admin/settings
     *
     * @test
     */
    public function testPostIndexAction()
    {
        $input = array(
            'site_name'        => 'Orchestra Platform',
            'site_description' => '',
            'site_registrable' => 'yes',

            'email_driver'     => 'smtp',
            'email_address'    => 'email@orchestraplatform.com',
            'email_host'       => 'orchestraplatform.com',
            'email_port'       => 25,
            'email_username'   => 'email@orchestraplatform.com',
            'email_password'   => '',
            'change_password'  => 'no',
            'email_encryption' => 'ssl',
            'email_sendmail'   => '/usr/bin/sendmail -t',
            'email_queue'      => 'no',
        );

        $memory = m::mock('\Orchestra\Memory\Provider')->makePartial();
        list(, $validator) = $this->bindDependencies();

        $memory->shouldReceive('put')->times(14)->andReturnNull()
            ->shouldReceive('get')->once()->with('email.password')->andReturn('foo');
        $validator->shouldReceive('on')->once()->with('smtp')->andReturn($validator)
            ->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);

        Foundation::shouldReceive('memory')->once()->andReturn($memory);
        Foundation::shouldReceive('handles')->once()->with('orchestra::settings', array())->andReturn('settings');
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();

        $this->call('POST', 'admin/settings', $input);
        $this->assertRedirectedTo('settings');
    }

    /**
     * Test POST /admin/settings with validation error.
     *
     * @test
     */
    public function testPostIndexActionGivenValidationError()
    {
        $input = array(
            'site_name'        => 'Orchestra Platform',
            'site_description' => '',
            'site_registrable' => 'yes',

            'email_driver'     => 'smtp',
            'email_address'    => 'email@orchestraplatform.com',
            'email_host'       => 'orchestraplatform.com',
            'email_port'       => 25,
            'email_username'   => 'email@orchestraplatform.com',
            'email_password'   => '',
            'change_password'  => 'no',
            'email_encryption' => 'ssl',
            'email_sendmail'   => '/usr/bin/sendmail -t',
            'email_queue'      => 'no',
        );

        list(, $validator) = $this->bindDependencies();

        $validator->shouldReceive('on')->once()->with('smtp')->andReturn($validator)
            ->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(true);

        Foundation::shouldReceive('handles')->once()->with('orchestra::settings', array())->andReturn('settings');

        $this->call('POST', 'admin/settings', $input);
        $this->assertRedirectedTo('settings');
        $this->assertSessionHasErrors();
    }

    /**
     * Test GET /admin/settings/migrate
     *
     * @test
     */
    public function testGetMigrateAction()
    {
        $asset   = m::mock('\Orchestra\Extension\Publisher\AssetManager')->makePartial();
        $migrate = m::mock('\Orchestra\Extension\Publisher\MigrateManager')->makePartial();

        $asset->shouldReceive('foundation')->once()->andReturnNull();
        $migrate->shouldReceive('foundation')->once()->andReturnNull();

        Foundation::shouldReceive('make')->once()->with('orchestra.publisher.asset')->andReturn($asset);
        Foundation::shouldReceive('make')->once()->with('orchestra.publisher.migrate')->andReturn($migrate);
        Foundation::shouldReceive('handles')->once()->with('orchestra::settings', array())->andReturn('settings');

        $this->call('GET', 'admin/settings/migrate');
        $this->assertRedirectedTo('settings');
    }
}
