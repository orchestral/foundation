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
    protected function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Http\Controllers\AdminController filters.
     *
     * @test
     */
    public function testFilters()
    {
        $stub = new StubAdminController();

        $middleware = [
            [
                'middleware' => 'orchestra.installable',
                'options' => [],
            ],
        ];

        $this->assertEquals($middleware, $stub->getMiddleware());
    }
}

class StubAdminController extends AdminController
{
    protected function setupMiddleware()
    {
        //
    }
}
