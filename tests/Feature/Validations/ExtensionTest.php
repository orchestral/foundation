<?php

namespace Orchestra\Tests\Feature\Validations;

use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Validations\Extension;

class ExtensionTest extends TestCase
{
    /** @test */
    public function it_declares_proper_signature()
    {
        $stub = $this->app->make(Extension::class);

        $this->assertInstanceOf('\Orchestra\Foundation\Validations\Extension', $stub);
        $this->assertInstanceOf('\Orchestra\Support\Validator', $stub);
    }
}
