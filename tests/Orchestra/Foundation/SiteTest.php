<?php namespace Orchestra\Foundation\TestCase;

use Mockery as m;
use Carbon\Carbon;
use Illuminate\Container\Container;
use Orchestra\Foundation\Site;

class SiteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        date_default_timezone_set('UTC');
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
        $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $auth = m::mock('\Illuminate\Auth\AuthManager')->makePartial();
        $memory = m::mock('\Orchestra\Memory\Provider');

        $stub = new Site($auth, $config, $memory);

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
        $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $auth = m::mock('\Illuminate\Auth\AuthManager')->makePartial();
        $memory = m::mock('\Orchestra\Memory\Provider');

        $stub = new Site($auth, $config, $memory);
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
        $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $auth = m::mock('\Illuminate\Auth\AuthManager')->makePartial();
        $memory = m::mock('\Orchestra\Memory\Provider');

        $stub = new Site($auth, $config, $memory);

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
        $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $auth = m::mock('\Illuminate\Auth\AuthManager')->makePartial();
        $memory = m::mock('\Orchestra\Memory\Provider');

        $stub = new Site($auth, $config, $memory);

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
     * Test Orchestra\Foundation\Site::toLocalTime() method given date as
     * string.
     *
     * @test
     * @group support
     */
    public function testToLocalTimeGivenDateAsString()
    {
        $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $auth = m::mock('\Illuminate\Auth\AuthManager')->makePartial();
        $memory = m::mock('\Orchestra\Memory\Provider');

        $config->shouldReceive('get')->once()->with('app.timezone', 'UTC')->andReturn('UTC');
        $auth->shouldReceive('guest')->once()->andReturn(true);

        $stub = with(new Site($auth, $config, $memory))->toLocalTime('2012-01-01 00:00:00');

        $this->assertEquals(new \DateTimeZone('UTC'), $stub->getTimezone());
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
        $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $auth = m::mock('\Illuminate\Auth\AuthManager')->makePartial();
        $memory = m::mock('\Orchestra\Memory\Provider');

        $config->shouldReceive('get')->once()->with('app.timezone', 'UTC')->andReturn('UTC');
        $auth->shouldReceive('guest')->once()->andReturn(true);

        $stub = with(new Site($auth, $config, $memory))->toLocalTime(new Carbon('2012-01-01 00:00:00'));
        $this->assertEquals(new \DateTimeZone('UTC'), $stub->getTimezone());
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
        $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $auth = m::mock('\Illuminate\Auth\AuthManager')->makePartial();
        $memory = m::mock('\Orchestra\Memory\Provider');

        $config->shouldReceive('get')->with('app.timezone', 'UTC')->andReturn('UTC');
        $auth->shouldReceive('guest')->once()->andReturn(false)
            ->shouldReceive('user')->once()->andReturn((object) array('id' => 1));
        $memory->shouldReceive('get')->once()->with('timezone.1', 'UTC')->andReturn('Asia/Kuala_Lumpur');

        $stub = with(new Site($auth, $config, $memory))->toLocalTime(new Carbon('2012-01-01 00:00:00'));

        $this->assertEquals(new \DateTimeZone('Asia/Kuala_Lumpur'), $stub->timezone);
        $this->assertEquals('2012-01-01 08:00:00', $stub->toDateTimeString());
    }

    /**
     * Test Orchestra\Foundation\Site::toGlobalTime() method return proper
     * datetime when is guest.
     *
     * @test
     * @group support
     */
    public function testFromLocalTimeReturnProperDateTimeWhenIsGuest()
    {
        $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $auth = m::mock('\Illuminate\Auth\AuthManager')->makePartial();
        $memory = m::mock('\Orchestra\Memory\Provider');

        $config->shouldReceive('get')->once()->with('app.timezone', 'UTC')->andReturn('UTC');
        $auth->shouldReceive('guest')->once()->andReturn(true);

        $stub = with(new Site($auth, $config, $memory))->fromLocalTime('2012-01-01 00:00:00');

        $this->assertEquals(new \DateTimeZone('UTC'), $stub->timezone);
    }

    /**
     * Test Orchestra\Foundation\Site::fromLocalTime() method return proper
     * datetime when is user.
     *
     * @test
     * @group support
     */
    public function testFromLocalTimeReturnProperDateTimeWhenIsUser()
    {
        $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $auth = m::mock('\Illuminate\Auth\AuthManager')->makePartial();
        $memory = m::mock('\Orchestra\Memory\Provider');

        $config->shouldReceive('get')->with('app.timezone', 'UTC')->andReturn('UTC');
        $auth->shouldReceive('guest')->once()->andReturn(false)
            ->shouldReceive('user')->once()->andReturn((object) array('id' => 1));
        $memory->shouldReceive('get')->once()->with('timezone.1', 'UTC')->andReturn('Asia/Kuala_Lumpur');

        $stub = with(new Site($auth, $config, $memory))->fromLocalTime('2012-01-01 08:00:00');

        $this->assertEquals(new \DateTimeZone('UTC'), $stub->timezone);
        $this->assertEquals('2012-01-01 00:00:00', $stub->toDateTimeString());
    }

    /**
     * Test Orchestra\Foundation\Site::convertToDateTime() method when
     * timezone is null.
     *
     * @test
     */
    public function testConvertToDateTimeMethodWhenTimezoneIsNull()
    {
        $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $auth = m::mock('\Illuminate\Auth\AuthManager')->makePartial();
        $memory = m::mock('\Orchestra\Memory\Provider');

        $stub = new Site($auth, $config, $memory);

        $output = $stub->convertToDateTime('2012-01-01 08:00:00');

        $this->assertInstanceOf('\Carbon\Carbon', $output);
        $this->assertEquals('2012-01-01 08:00:00', $output->toDateTimeString());
    }
}
