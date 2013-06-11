<?php namespace Orchestra\Foundation\Tests\Routing;

use Mockery as m;
use Orchestra\Services\TestCase;

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

		\Orchestra\Support\Facades\App::shouldReceive('memory')->once()->andReturn($memory);
		\Orchestra\Support\Facades\Form::shouldReceive('of')->once()
			->with('orchestra.settings', m::type('Closure'))->andReturn('form');
		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
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

		\Orchestra\Support\Facades\App::shouldReceive('memory')->once()->andReturn($memory);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\Setting')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::settings')->andReturn('settings');
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('success', m::any())->andReturn(null);

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

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\Setting')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::settings')->andReturn('settings');

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

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.publisher.asset')->andReturn($asset);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.publisher.migrate')->andReturn($migrate);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::settings')->andReturn('settings');

		$this->call('GET', 'admin/settings/update');
		$this->assertRedirectedTo('settings');
	}
}
