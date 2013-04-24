<?php namespace Orchestra\Foundation\Tests;

class HelpersTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$app = \Mockery::mock('Application');
		$app->shouldReceive('instance')->andReturn(true);

		\Orchestra\Support\Facades\App::setFacadeApplication($app);
		\Illuminate\Support\Facades\Config::setFacadeApplication($app);
	}
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test memorize() method.
	 *
	 * @test
	 */
	public function testMemorizeMethod()
	{
		\Orchestra\Support\Facades\App::swap($app = \Mockery::mock('\Orchestra\Foundation\Application'));

		$app->shouldReceive('memory')->once()->andReturn($app)
			->shouldReceive('get')->once()->with('site.name', null)->andReturn('Orchestra');

		$this->assertEquals('Orchestra', memorize('site.name'));
	}

	/**
	 * Test handles() method.
	 *
	 * @test
	 */
	public function testHandlesMethod()
	{
		$request = \Mockery::mock('\Illuminate\Http\Request');
		$request->shouldReceive('ajax')->andReturn(null);

		$app = new \Illuminate\Foundation\Application($request);
		$app['url'] = ($url = \Mockery::mock('Url'));

		\Illuminate\Support\Facades\Facade::setFacadeApplication($app);
		\Illuminate\Support\Facades\Config::swap($config = \Mockery::mock('Config'));

		$config->shouldReceive('get')->once()->with('app::handles', '/')->andReturn('/')
			->shouldReceive('get')->twice()->with('orchestra/foundation::handles', '/')->andReturn('admin');
		
		$url->shouldReceive('to')->once()->with('/', array(), null)->andReturn('/')
			->shouldReceive('to')->once()->with('/info', array(), null)->andReturn('info')
			->shouldReceive('to')->twice()->with('admin/installer', array(), null)->andReturn('admin/installer');

		$this->assertEquals('/', handles('app::/'));
		$this->assertEquals('info', handles('info'));
		$this->assertEquals('admin/installer', handles('orchestra/foundation::installer'));
		$this->assertEquals('admin/installer', handles('orchestra/foundation::installer/'));
	}
	
}