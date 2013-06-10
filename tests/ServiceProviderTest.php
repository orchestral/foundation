<?php namespace Orchestra\Foundation\Tests;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Orchestra\Services\TestCase;

class ServiceProviderTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test instance of `orchestra.mail`.
	 */
	public function testInstanceOfOrchestraMail()
	{
		$stub = App::make('orchestra.mail');
		$this->assertInstanceOf('\Orchestra\Foundation\Mail', $stub);
	}

	/**
	 * Test instance of `orchestra.publisher`.
	 */
	public function testInstanceOfOrchestraPublisher()
	{
		$stub = App::make('orchestra.publisher');
		$this->assertInstanceOf('\Orchestra\Foundation\Publisher\PublisherManager', $stub);

		$stub = App::make('orchestra.publisher.ftp');
		$this->assertInstanceOf('\Orchestra\Support\Ftp', $stub);
	}

	/**
	 * Test instance of `orchestra.memory`.
	 *
	 * @test
	 */
	public function testInstanceOfOrchestraMemory()
	{
		$stub = App::make('orchestra.memory')->driver('user');
		$this->assertInstanceOf('\Orchestra\Services\UserMetaRepository', $stub);
	}

	/**
	 * Test instance of eloquents.
	 *
	 * @test
	 */
	public function testInstanceOfEloquents()
	{
		$stub = App::make('orchestra.role');
		$this->assertInstanceOf('\Orchestra\Model\Role', $stub);

		$stub = App::make('orchestra.user');
		$this->assertInstanceOf('\Orchestra\Model\User', $stub);
	}

	/**
	 * Test instance of auth reminder.
	 *
	 * @test
	 */
	public function testInstanceOfAuthReminder()
	{
		$app  = App::getFacadeApplication();
		$app['auth.reminder.repository'] = m::mock('\Illuminate\Auth\Reminders\DatabaseReminderRepository');
		$app['auth'] = $user = m::mock('\Illuminate\Auth\UserProviderInterface');

		$user->shouldReceive('driver')->once()->andReturn($user)
			->shouldReceive('getProvider')->once()->andReturn($user);

		$stub = App::make('auth.reminder');
		$this->assertInstanceOf('\Orchestra\Foundation\Reminders\PasswordBroker', $stub);
	}

	/**
	 * Test list of provides.
	 *
	 * @test
	 */
	public function testListOfProvides()
	{
		$app = App::getFacadeApplication();

		$foundation = new \Orchestra\Foundation\FoundationServiceProvider($app);
		$site       = new \Orchestra\Foundation\SiteServiceProvider($app);
		$reminder   = new \Orchestra\Foundation\Reminders\ReminderServiceProvider($app);

		$this->assertEquals(array('orchestra.app', 'orchestra.installed'), $foundation->provides());
		$this->assertEquals(array(
			'orchestra.mail', 'orchestra.publisher', 'orchestra.publisher.ftp', 
			'orchestra.site', 'orchestra.role', 'orchestra.user',
		), $site->provides());
		$this->assertEquals(array('auth.reminder', 'auth.reminder.repository', 'command.auth.reminders'), $reminder->provides());
	}
}
