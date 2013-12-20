<?php namespace Orchestra\Foundation\Tests;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Foundation\UserMetaRepository;

class UserMetaRepositoryTest extends \PHPUnit_Framework_TestCase
{
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
        $this->app = new Container;
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
     * Test Orchestra\Foundation\UserMetaRepository::initiate()
     * method.
     *
     * @test
     */
    public function testInitiateMethod()
    {
        $stub = new UserMetaRepository('meta', array(), $this->app);

        $this->assertEquals(array(), $stub->initiate());
    }

    /**
     * Test Orchestra\Foundation\UserMetaRepository::initiate() method.
     *
     * @test
     */
    public function testRetrieveMethod()
    {
        $app = $this->app;

        $value = (object) array(
            'id' => 2,
            'value' => 'foobar',
        );

        $app->instance('Orchestra\Model\UserMeta', $eloquent = m::mock('UserMeta'));

        $eloquent->shouldReceive('newInstance')->twice()->andReturn($eloquent)
            ->shouldReceive('search')->once()->with('foo', 1)->andReturn($fooQuery = m::mock('Model\Query'))
            ->shouldReceive('search')->once()->with('foobar', 1)->andReturn($foobarQuery = m::mock('Model\Query'));

        $fooQuery->shouldReceive('first')->andReturn($value);
        $foobarQuery->shouldReceive('first')->andReturn(null);

        $stub = new UserMetaRepository('meta', array(), $app);

        $this->assertEquals('foobar', $stub->retrieve('foo/user-1'));
        $this->assertNull($stub->retrieve('foobar/user-1'));
    }

    /**
     * Test Orchestra\Foundation\UserMetaRepository::finish() method.
     *
     * @test
     */
    public function testFinishMethod()
    {
        $app = $this->app;

        $value = m::mock('stdClass', array(
            'id' => 2,
            'value' => 'foobar',
        ));

        $items = array(
            'foo/user-1'    => '',
            'foobar/user-1' => 'foo',
            'foo/user-2'    => ':to-be-deleted:',
            'foo/user-'     => ''
        );

        $app->instance('Orchestra\Model\UserMeta', $eloquent = m::mock('UserMeta'));

        $eloquent->shouldReceive('newInstance')->times(4)->andReturn($eloquent)
            ->shouldReceive('search')->once()->with('foo', 1)->andReturn($fooQuery = m::mock('Model\Query'))
            ->shouldReceive('search')->once()->with('foobar', 1)->andReturn($foobarQuery = m::mock('Model\Query'))
            ->shouldReceive('search')->once()->with('foo', 2)->andReturn($foobarQuery)
            ->shouldReceive('save')->once()->andReturnNull();

        $fooQuery->shouldReceive('first')->andReturn($value);
        $foobarQuery->shouldReceive('first')->andReturnNull();

        $value->shouldReceive('save')->once()->andReturnNull();

        $stub = new UserMetaRepository('meta', array(), $app);

        $this->assertTrue($stub->finish($items));
    }
}
