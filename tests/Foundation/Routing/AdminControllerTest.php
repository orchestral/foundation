<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;

class AdminControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Routing\AdminController filters.
     *
     * @test
     */
    public function testFilters()
    {
        $stub = new StubAdminController;

        $beforeFilter = [
            [
                'original'   => 'orchestra.installable',
                'filter'     => 'orchestra.installable',
                'parameters' => [],
                'options'    => [],
            ]
        ];

        $this->assertEquals($beforeFilter, $stub->getBeforeFilters());
        $this->assertEquals(['Orchestra\Foundation\Middleware\UseBackendTheme' => []], $stub->getMiddleware());
    }
}

class StubAdminController extends \Orchestra\Foundation\Routing\AdminController
{
    protected function setupFilters()
    {
        //
    }
}
