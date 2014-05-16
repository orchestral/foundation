<?php namespace Orchestra\Foundation\Tests\Validation;

use Orchestra\Foundation\Validation\Extension;

class ExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Orchestra\Foundation\Validation\Extension.
     *
     * @test
     */
    public function testInstance()
    {
        $stub = new Extension;

        $this->assertInstanceOf('\Orchestra\Foundation\Validation\Extension', $stub);
        $this->assertInstanceOf('\Orchestra\Support\Validator', $stub);
    }
}
