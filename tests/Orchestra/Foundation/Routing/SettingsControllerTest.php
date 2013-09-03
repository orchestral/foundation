<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Support\Facades\App as Orchestra;
use Orchestra\Support\Facades\Form;
use Orchestra\Support\Facades\Messages;

class SettingsControllerTest extends TestCase {
	
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test GET /admin/settings
	 *
	 * @test
	 */
	public function testGetIndexAction()
	{
		$memory = m::mock('Memory');

		$memory->shouldReceive('get')->times(12)->andReturn('');

		Orchestra::shouldReceive('memory')->once()->andReturn($memory);
		Form::shouldReceive('of')->once()
			->with('orchestra.settings', m::type('Closure'))->andReturn('form');
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
		$validation = m::mock('SettingValidation');

		$memory->shouldReceive('put')->times(12)->andReturn(null)
			->shouldReceive('get')->once()->with('email.password')->andReturn('foo');

		$validation->shouldReceive('on')->once()->with('smtp')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(false);

		Orchestra::shouldReceive('memory')->once()->andReturn($memory);
		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\Setting')->andReturn($validation);
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

		$validation = m::mock('SettingValidation');

		$validation->shouldReceive('on')->once()->with('smtp')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(true);

		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\Setting')->andReturn($validation);
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
