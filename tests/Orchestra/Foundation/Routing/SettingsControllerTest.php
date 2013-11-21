<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Testing\TestCase;
use Orchestra\Support\Facades\App as Orchestra;
use Orchestra\Support\Facades\Form;
use Orchestra\Support\Facades\Messages;

class SettingsControllerTest extends TestCase
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
        $memory = m::mock('Memory');
        list($presenter,) = $this->bindDependencies();

        $memory->shouldReceive('get')->times(12)->andReturn('');
        $presenter->shouldReceive('form')->once()->andReturn('edit.settings');

        Orchestra::shouldReceive('memory')->once()->andReturn($memory);
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

        $memory = m::mock('Memory');
        list(, $validator) = $this->bindDependencies();

        $memory->shouldReceive('put')->times(12)->andReturn(null)
            ->shouldReceive('get')->once()->with('email.password')->andReturn('foo');
        $validator->shouldReceive('on')->once()->with('smtp')->andReturn($validator)
            ->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);

        Orchestra::shouldReceive('memory')->once()->andReturn($memory);
        Orchestra::shouldReceive('handles')->once()->with('orchestra::settings')->andReturn('settings');
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturn(null);

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

        Orchestra::shouldReceive('handles')->once()->with('orchestra::settings')->andReturn('settings');

        $this->call('POST', 'admin/settings', $input);
        $this->assertRedirectedTo('settings');
        $this->assertSessionHasErrors();
    }

    /**
     * Test GET /admin/settings/update
     *
     * @test
     */
    public function testGetUpdateAction()
    {
        $asset   = m::mock('AssetPublisher');
        $migrate = m::mock('MigratePublisher');

        $asset->shouldReceive('foundation')->once()->andReturn(null);
        $migrate->shouldReceive('foundation')->once()->andReturn(null);

        Orchestra::shouldReceive('make')->once()->with('orchestra.publisher.asset')->andReturn($asset);
        Orchestra::shouldReceive('make')->once()->with('orchestra.publisher.migrate')->andReturn($migrate);
        Orchestra::shouldReceive('handles')->once()->with('orchestra::settings')->andReturn('settings');

        $this->call('GET', 'admin/settings/update');
        $this->assertRedirectedTo('settings');
    }
}
