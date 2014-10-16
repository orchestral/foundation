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
}
