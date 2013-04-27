<?php namespace Orchestra\Foundation\Tests;

use Mockery as m;
use Orchestra\Foundation\Site;

class SiteTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$app = m::mock('Application');
		$app->shouldReceive('instance')->andReturn(true);

		\Illuminate\Support\Facades\Auth::setFacadeApplication($app);
		\Illuminate\Support\Facades\Config::setFacadeApplication($app);
		\Illuminate\Support\Facades\Config::swap($config = m::mock('Config'));

		$config->shouldReceive('get')->with('app.timezone', 'UTC')->andReturn('UTC');
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
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
		$stub = new Site;

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
		$stub = new Site;
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
		$stub = new Site;

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
		$stub = new Site;

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
	 * Test localtime() return proper datetime when is guest.
	 *
	 * @test
	 * @group support
	 */
	public function testLocalTimeReturnProperDateTimeWhenIsGuest()
	{
		$auth = m::mock('Illuminate\Auth\Guard');

		\Illuminate\Support\Facades\Auth::swap($auth);

		$auth->shouldReceive('guest')->andReturn(true);

		$stub = new Site;

		$this->assertEquals(new \DateTimeZone('UTC'), 
			$stub->localtime('2012-01-01 00:00:00')->getTimezone());
	}

	/**
	 * Test localtime() return proper datetime when is user.
	 *
	 * @test
	 * @group support
	 */
	public function testLocalTimeReturnProperDateTimeWhenIsUser()
	{
		$auth = m::mock('Illuminate\Auth\Guard');
		$user = m::mock('Orchestra\Foundation\Model\User');

		\Illuminate\Support\Facades\Auth::swap($auth);

		$date = new \DateTime('2012-01-01 00:00:00');

		$auth->shouldReceive('guest')->andReturn(false)
			->shouldReceive('user')->andReturn($user);

		$user->shouldReceive('localtime')->with($date)
			->andReturn($date->setTimeZone(new \DateTimeZone('Asia/Kuala_Lumpur')));

		$stub = new Site;

		$this->assertEquals(new \DateTimeZone('Asia/Kuala_Lumpur'),
				$stub->localtime($date)->getTimezone());
	}
}
