<?php

namespace Orchestra\Foundation\TestCase\Validation;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Foundation\Validation\Extension;

class ExtensionTest extends TestCase
{
    /**
     * Test Orchestra\Foundation\Validation\Extension.
     *
     * @test
     */
    public function testInstance()
    {
        $events = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory = m::mock('\Illuminate\Contracts\Validation\Factory');

        $stub = new Extension($factory, $events);

        $this->assertInstanceOf('\Orchestra\Foundation\Validation\Extension', $stub);
        $this->assertInstanceOf('\Orchestra\Support\Validator', $stub);
    }
}
