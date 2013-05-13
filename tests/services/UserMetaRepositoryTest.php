<?php namespace Orchestra\Foundation\Tests\Services;

use Mockery as m;
use Orchestra\Services\UserMetaRepository;

class UserMetaRepositoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	private $app = null;

	/**
	 * UserMeta instance.
	 *
	 * @var Orchestra\Model\UserMeta
	 */
	private $model = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$request = m::mock('\Illuminate\Http\Request');
		$request->shouldReceive('ajax')->andReturn(null)
			->shouldReceive('wantsJson')->andReturn(false);

		$this->app   = new \Illuminate\Foundation\Application($request);
		$this->model = m::mock('UserMeta');
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
	 * Test Orchestra\Services\UserMetaRepository::initiate() method.
	 *
	 * @test
	 */
	public function testInitiateMethod()
	{
		$app = $this->app;
		$app['config'] = $config = m::mock('Config');
		$eloquent = $this->model;
		$fooUser = m::mock('UserMeta');
		$fooNull = m::mock('UserMeta');

		$fooUser->shouldReceive('first')->twice()->andReturn($eloquent);
		$fooNull->shouldReceive('first')->once()->andReturn(null);

		$config->shouldReceive('get')->with('orchestra/memory::user.meta', array())->once()->andReturn(array());
		$eloquent->shouldReceive('newInstance')->times(4)->andReturn($eloquent)
			->shouldReceive('search')->with('foo', 1)->once()->andReturn($fooUser)
			->shouldReceive('search')->with('foo', 2)->once()->andReturn($fooUser)
			->shouldReceive('search')->with('foobar', 1)->once()->andReturn($fooNull)
			->shouldReceive('save')->twice()->andReturn(true)
			->shouldReceive('delete')->once()->andReturn(true);

		$app->instance('Orchestra\Model\UserMeta', $eloquent);
		
		$stub   = new UserMetaRepository($app, 'meta');
		$refl   = new \ReflectionObject($stub);
		$data   = $refl->getProperty('data');
		$keyMap = $refl->getProperty('keyMap');
		$model  = $refl->getProperty('model');
		$data->setAccessible(true);
		$keyMap->setAccessible(true);
		$model->setAccessible(true);

		$data->setValue($stub, array(
			'foo/user-1'    => 'foobar',
			'foobar/user-1' => 'foo',
			'foo/user-2'    => ':to-be-deleted:'
		));

		$keyMap->setValue($stub, array(
			'foo/user-1' => array('id' => 5, 'value' => '', 'checksum' => md5('')),
			'foo/user-2' => array('id' => 6, 'value' => '', 'checksum' => md5('')),
		));
		
		$stub->finish();
	}

	/**
	 * Test Orchestra\Services\UserMetaRepository::get() method.
	 *
	 * @test
	 */
	public function testGetMethod()
	{
		$app = $this->app;
		$app['config'] = $config = m::mock('Config');
		$eloquent = $this->model;

		$foo = (object) array(
			'id'    => 1,
			'value' => 'foobar',
		);

		$fooResult = m::mock('UserMeta');
		$foobarResult = m::mock('UserMeta');

		$fooResult->shouldReceive('first')->once()->andReturn($foo);
		$foobarResult->shouldReceive('first')->once()->andReturn(null);

		$eloquent->shouldReceive('search')->with('foo', 1)->once()->andReturn($fooResult)
			->shouldReceive('search')->with('foobar', 1)->once()->andReturn($foobarResult);
		$config->shouldReceive('get')->with('orchestra/memory::user.meta', array())->once()->andReturn(array());

		$app->bind('Orchestra\Model\UserMeta', function () use ($eloquent)
		{
			return $eloquent;
		});

		$stub = new UserMetaRepository($app, 'meta');
		$this->assertEquals('foobar', $stub->get('foo.1'));
		$this->assertEquals(null, $stub->get('foobar.1'));
	}

	/**
	 * Test Orchestra\Services\UserMetaRepository::forget() method.
	 *
	 * @test
	 */
	public function testForgetMethod()
	{
		$app = $this->app;
		$app['config'] = $config = m::mock('Config');
		$eloquent = $this->model;
		
		$config->shouldReceive('get')->with('orchestra/memory::user.meta', array())->once()->andReturn(array());

		$app->bind('Orchestra\Model\UserMeta', function () use ($eloquent)
		{
			return $eloquent;
		});

		$stub  = new UserMetaRepository($app, 'meta');
		$refl  = new \ReflectionObject($stub);
		$data = $refl->getProperty('data');
		$data->setAccessible(true);

		$data->setValue($stub, array(
			'foo/user-1'   => 'foobar',
			'hello/user-1' => 'foobar',
		));

		$this->assertEquals('foobar', $stub->get('foo.1'));
		$stub->forget('foo.1');
		$this->assertNull($stub->get('foo.1'));
	}
	
}
