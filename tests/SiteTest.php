<?php namespace Orchestra\Foundation\Tests;

use Mockery as m;
use Orchestra\Foundation\Site;

class SiteTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	private $app = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$this->app = new \Illuminate\Container\Container;
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
	 * Test Orchestra\Foundation\Site::get() method.
	 *
	 * @test
	 * @group support
	 */
	public function testGetMethod()
	{
		$stub = new Site($this->app);

		$refl = new \ReflectionObject($stub);
		$items = $refl->getProperty('items');
		$items->setAccessible(true);
		$items->setValue($stub, array(
			'title'       => 'Hello World',
			'description' => 'Just another Hello World',
		));

		$this->assertEquals('Hello World', $stub->get('title'));
		$this->assertNull($stub->get('title.foo'));
	}

	/**
	 * Test Orchestra\Foundation\Site::set() method.
	 *
	 * @test
	 * @group support
	 */
	public function testSetMethod()
	{
		$stub = new Site($this->app);
		$stub->set('title', 'Foo');
		$stub->set('foo.bar', 'Foobar');

		$expected = array('title' => 'Foo', 'foo' => array('bar' => 'Foobar'));
		$this->assertEquals($expected, $stub->all());
	}

	/**
	 * Test Orchestra\Foundation\Site::has() method.
	 *
	 * @test
	 * @group support
	 */
	public function testHasMethod()
	{
		$stub = new Site($this->app);

		$refl = new \ReflectionObject($stub);
		$items = $refl->getProperty('items');
		$items->setAccessible(true);
		$items->setValue($stub, array(
			'title'       => 'Hello World',
			'description' => 'Just another Hello World',
			'hello'       => null,
		));

		$this->assertTrue($stub->has('title'));
		$this->assertFalse($stub->has('title.foo'));
		$this->assertFalse($stub->has('hello'));
	}

	/**
	 * Test Orchestra\Foundation\Site::forget() method.
	 *
	 * @test
	 * @group support
	 */
	public function testForgetMethod()
	{
		$stub = new Site($this->app);

		$refl = new \ReflectionObject($stub);
		$items = $refl->getProperty('items');
		$items->setAccessible(true);
		$items->setValue($stub, array(
			'title'       => 'Hello World',
			'description' => 'Just another Hello World',
			'hello'       => null,
			'foo'         => array(
				'hello' => 'foo',
				'bar'   => 'foobar',
			),
		));

		$stub->forget('title');
		$stub->forget('hello');
		$stub->forget('foo.bar');

		$this->assertFalse($stub->has('title'));
		$this->assertTrue($stub->has('description'));
		$this->assertFalse($stub->has('hello'));
		$this->assertEquals(array('hello' => 'foo'), $stub->get('foo'));
	}

	/**
	 * Test Orchestra\Foundation\Site::toLocalTime() method return proper 
	 * datetime when is guest.
	 *
	 * @test
	 * @group support
	 */
	public function testToLocalTimeReturnProperDateTimeWhenIsGuest()
	{
		$app = $this->app;

		$app['config'] = $config = m::mock('Config\Manager');
		$app['auth']   = $auth = m::mock('Auth\Guard');

		$config->shouldReceive('get')->once()->with('app.timezone', 'UTC')->andReturn('UTC');
		$auth->shouldReceive('guest')->once()->andReturn(true);

		$stub = new Site($app);

		$this->assertEquals(new \DateTimeZone('UTC'), 
			$stub->toLocalTime('2012-01-01 00:00:00')->getTimezone());
	}

	/**
	 * Test Orchestra\Foundation\Site::toLocalTime() method return proper 
	 * datetime when is user.
	 *
	 * @test
	 * @group support
	 */
	public function testToLocalTimeReturnProperDateTimeWhenIsUser()
	{
		$app = $this->app;

		$app['config'] = $config = m::mock('Config\Manager');
		$app['auth'] = $auth = m::mock('Auth\Guard');
		$app['orchestra.memory'] = $memory = m::mock('Memory');

		$config->shouldReceive('get')->with('app.timezone', 'UTC')->andReturn('UTC');
		$auth->shouldReceive('guest')->once()->andReturn(false)
			->shouldReceive('user')->once()->andReturn((object) array('id' => 1));
		$memory->shouldReceive('make')->once()->with('user')->andReturn($memory)
			->shouldReceive('get')->once()->with('timezone.1', 'UTC')->andReturn('Asia/Kuala_Lumpur');

		$date = new \DateTime('2012-01-01 00:00:00');

		$stub = new Site($app);

		$this->assertEquals(new \DateTimeZone('Asia/Kuala_Lumpur'),
				$stub->toLocalTime($date)->getTimezone());
	}
}
