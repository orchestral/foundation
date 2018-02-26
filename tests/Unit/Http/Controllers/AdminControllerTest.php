<?php

namespace Orchestra\Tests\Unit\Http\Controllers;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Foundation\Http\Controllers\AdminController;

class AdminControllerTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_defines_expected_middlewares()
    {
        $stub = new class() extends AdminController {
            protected function onCreate()
            {
                //
            }
        };

        $middleware = [
            [
                'middleware' => 'orchestra.installable',
                'options' => [],
            ],
        ];

        $this->assertEquals($middleware, $stub->getMiddleware());
    }
}
