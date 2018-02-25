<?php

namespace Orchestra\Tests\Feature\Validation;

use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Validation\Extension;

class ExtensionTest extends TestCase
{
    /** @test */
    public function it_declares_proper_signature()
    {
        $stub = $this->app->make(Extension::class);

        $this->assertInstanceOf('\Orchestra\Foundation\Validation\Extension', $stub);
        $this->assertInstanceOf('\Orchestra\Support\Validator', $stub);
    }
}
