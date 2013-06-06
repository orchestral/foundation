<?php namespace Orchestra\Foundation\Tests\Installation;

use Mockery as m;
use Orchestra\Foundation\Installation\Installer;

class InstallerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$this->app = new \Illuminate\Container\Container;
		$this->app['path'] = '/var/app';

		$this->app['translator'] = $translator = m::mock('Translator');

		$translator->shouldReceive('trans')->andReturn('translated');

		\Illuminate\Support\Facades\Facade::setFacadeApplication($this->app);
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		unset($this->app);
		m::close();
	}

	/**
	 * Get files mock.
	 *
	 * @access private
	 * @return File
	 */
	private function getFilesMock()
	{
		$files = m::mock('File');
		$files->shouldReceive('exists')->once()->with('/var/app/orchestra/installer.php')->andReturn(true)
			->shouldReceive('requireOnce')->once()->with('/var/app/orchestra/installer.php')->andReturn(null);

		return $files;
	}

	/**
	 * Get User input.
	 *
	 * @access private
	 * @return array
	 */
	private function getUserInput()
	{
		return array(
			'site_name' => 'Orchestra Platform',
			'email'     => 'admin@orchestraplatform.com',
			'password'  => '123456',
			'fullname'  => 'Administrator',
		);
	}

	/**
	 * Get validation rules.
	 *
	 * @access private
	 * @return array
	 */
	private function getValidationRules()
	{
		return array(
			'email'     => array('required', 'email'),
			'password'  => array('required'),
			'fullname'  => array('required'),
			'site_name' => array('required'),
		);
	}

	/**
	 * Test Orchestra\Foundation\Installation\Installer::migrate() method.
	 *
	 * @test
	 */
	public function testMigrateMethod()
	{
		$app = $this->app;
		$app['files'] = $this->getFilesMock();
		$app['orchestra.publisher.migrate'] = $migrate = m::mock('migrate');
		$app['events'] = $events = m::mock('Event');

		$migrate->shouldReceive('foundation')->once()->andReturn(null);
		$events->shouldReceive('fire')->once()->with('orchestra.install.schema')->andReturn(null);

		$stub = new Installer($app);
		$this->assertTrue($stub->migrate());
	}
	/**
	 * Test Orchestra\Foundation\Installation\Installer::createAdmin() method.
	 *
	 * @test
	 */
	public function testCreateAdminMethod()
	{
		$app = $this->app;
		$app['files'] = $this->getFilesMock();
		$app['validator'] = $validator = m::mock('Validator');
		$app['orchestra.role'] = $role = m::mock('Role');
		$app['orchestra.user'] = $user = m::mock('User');
		$app['orchestra.messages'] = $messages = m::mock('Messages');
		$app['events'] = $events = m::mock('Event');
		$app['orchestra.memory'] = $memory = m::mock('Memory');
		$app['config'] = $config = m::mock('Config');
		$app['orchestra.acl'] = $acl = m::mock('Acl');

		$input = $this->getUserInput();
		$rules = $this->getValidationRules();

		$validator->shouldReceive('make')->once()->with($input, $rules)->andReturn($validator)
			->shouldReceive('fails')->once()->andReturn(false);
		$user->shouldReceive('newQuery')->twice()->andReturn($user)
			->shouldReceive('all')->once()->andReturn(null)
			->shouldReceive('fillable')->once()->with(array('email', 'password', 'fullname', 'status'))->andReturn(null)
			->shouldReceive('fill')->once()->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null)
			->shouldReceive('roles')->once()->andReturn($user)
			->shouldReceive('sync')->once()->with(array(1))->andReturn(null);
		$role->shouldReceive('newQuery')->once()->andReturn($role)
			->shouldReceive('lists')->once()->with('name', 'id')->andReturn(array('admin', 'member'));
		$events->shouldReceive('fire')->once()->with('orchestra.install: user', array($user, $input))->andReturn(null)
			->shouldReceive('fire')->once()->with('orchestra.install: acl', array($acl))->andReturn(null);
		$memory->shouldReceive('make')->once()->andReturn($memory)
			->shouldReceive('put')->once()->with('site.name', $input['site_name'])->andReturn(null)
			->shouldReceive('put')->once()->with('site.theme', array('frontend' => 'default', 'backend' => 'default'))->andReturn(null)
			->shouldReceive('put')->once()->with('email', 'email-config')->andReturn(null)
			->shouldReceive('put')->once()->with('email.from', array('name' => $input['site_name'], 'address' => $input['email']))->andReturn(null);
		$config->shouldReceive('get')->once()->with('orchestra/foundation::roles.admin', 1)->andReturn(1)
			->shouldReceive('get')->once()->with('mail')->andReturn('email-config');
		$acl->shouldReceive('make')->once()->with('orchestra')->andReturn($acl)
			->shouldReceive('actions')->once()->andReturn($acl)
			->shouldReceive('roles')->once()->andReturn($acl)
			->shouldReceive('fill')->twice()->andReturn(null)
			->shouldReceive('allow')->once()->andReturn(null)
			->shouldReceive('attach')->once()->with($memory)->andReturn(null);

		$messages->shouldReceive('add')->once()->with('success', 'translated')->andReturn(null);

		$stub = new Installer($app);
		$this->assertTrue($stub->createAdmin($input, false));
	}

	/**
	 * Test Orchestra\Foundation\Installation\Installer::createAdmin() method 
	 * with validation errors.
	 *
	 * @test
	 */
	public function testCreateAdminMethodWithValidationErrors()
	{
		$app = $this->app;
		$app['files'] = $this->getFilesMock();
		$app['validator'] = $validator = m::mock('Validator');
		$app['session'] = $session = m::mock('Session');

		$input = $this->getUserInput();
		$rules = $this->getValidationRules();

		$validator->shouldReceive('make')->once()->with($input, $rules)->andReturn($validator)
			->shouldReceive('fails')->once()->andReturn(true)
			->shouldReceive('messages')->once()->andReturn('foo-errors');
		$session->shouldReceive('flash')->once()->with('errors', 'foo-errors')->andReturn(null);


		$stub = new Installer($app);
		$this->assertFalse($stub->createAdmin($input));
	}

	/**
	 * Test Orchestra\Foundation\Installation\Installer::createAdmin() method 
	 * throws exception.
	 *
	 * @test
	 */
	public function testCreateAdminMethodThrowsException()
	{
		$app = $this->app;
		$app['files'] = $this->getFilesMock();
		$app['validator'] = $validator = m::mock('Validator');
		$app['orchestra.user'] = $user = m::mock('User');
		$app['orchestra.messages'] = $messages = m::mock('Messages');

		$input = $this->getUserInput();
		$rules = $this->getValidationRules();

		$validator->shouldReceive('make')->once()->with($input, $rules)->andReturn($validator)
			->shouldReceive('fails')->once()->andReturn(false);
		$user->shouldReceive('newQuery')->once()->andReturn($user)
			->shouldReceive('all')->once()->andReturn(array('not so empty'));
		$messages->shouldReceive('add')->once()->with('error', 'translated')->andReturn(null);

		$stub = new Installer($app);
		$this->assertFalse($stub->createAdmin($input, false));
	}
}
