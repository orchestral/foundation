<?php

namespace Orchestra\Tests\Unit;

use Mockery as m;
use Orchestra\Foundation\Meta;
use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        date_default_timezone_set('UTC');
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Meta::get() method.
     *
     * @test
     * @group support
     */
    public function testGetMethod()
    {
        $stub = new Meta();

        $refl = new \ReflectionObject($stub);
        $items = $refl->getProperty('items');
        $items->setAccessible(true);
        $items->setValue($stub, [
            'title' => 'Hello World',
            'description' => 'Just another Hello World',
        ]);

        $this->assertEquals('Hello World', $stub->get('title'));
        $this->assertNull($stub->get('title.foo'));
    }

    /**
     * Test Orchestra\Foundation\Meta::set() method.
     *
     * @test
     * @group support
     */
    public function testSetMethod()
    {
        $stub = new Meta();
        $stub->set('title', 'Foo');
        $stub->set('foo.bar', 'Foobar');

        $expected = ['title' => 'Foo', 'foo' => ['bar' => 'Foobar']];
        $this->assertEquals($expected, $stub->all());
    }

    /**
     * Test Orchestra\Foundation\Meta::has() method.
     *
     * @test
     * @group support
     */
    public function testHasMethod()
    {
        $stub = new Meta();

        $refl = new \ReflectionObject($stub);
        $items = $refl->getProperty('items');
        $items->setAccessible(true);
        $items->setValue($stub, [
            'title' => 'Hello World',
            'description' => 'Just another Hello World',
            'hello' => null,
        ]);

        $this->assertTrue($stub->has('title'));
        $this->assertFalse($stub->has('title.foo'));
        $this->assertFalse($stub->has('hello'));
    }

    /**
     * Test Orchestra\Foundation\Meta::forget() method.
     *
     * @test
     * @group support
     */
    public function testForgetMethod()
    {
        $stub = new Meta();

        $refl = new \ReflectionObject($stub);
        $items = $refl->getProperty('items');
        $items->setAccessible(true);
        $items->setValue($stub, [
            'title' => 'Hello World',
            'description' => 'Just another Hello World',
            'hello' => null,
            'foo' => [
                'hello' => 'foo',
                'bar' => 'foobar',
            ],
        ]);

        $stub->forget('title');
        $stub->forget('hello');
        $stub->forget('foo.bar');

        $this->assertFalse($stub->has('title'));
        $this->assertTrue($stub->has('description'));
        $this->assertFalse($stub->has('hello'));
        $this->assertEquals(['hello' => 'foo'], $stub->get('foo'));
    }
}
