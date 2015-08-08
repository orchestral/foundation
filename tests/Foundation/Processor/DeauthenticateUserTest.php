<?php namespace Orchestra\Foundation\Processor\TestCase;

use Mockery as m;
use Orchestra\Foundation\Processor\DeauthenticateUser;

class DeauthenticateUserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Processor\AuthenticateUser::logout()
     * method.
     *
     * @test
     */
    public function testLogoutMethod()
    {
        $listener = m::mock('\Orchestra\Contracts\Auth\Listener\DeauthenticateUser');
        $auth = m::mock('\Orchestra\Contracts\Auth\Guard');

        $stub = new DeauthenticateUser($auth);

        $auth->shouldReceive('logout')->once()->andReturnNull();

        $listener->shouldReceive('userHasLoggedOut')->once()->andReturn('logged.out');

        $this->assertEquals('logged.out', $stub->logout($listener));
    }
}
