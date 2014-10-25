<?php namespace Orchestra\Foundation\Bootstrap\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Orchestra\Foundation\Testing\TestCase;

class UserAccessPolicyTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /**
     * Test Orchestra\Foundation\Bootstrap\UserAccessPolicy::bootstrap()
     * method.
     *
     * @test
     */
    public function testBootstrapMethod()
    {
        $app = App::getFacadeApplication();

        $app->make('Orchestra\Foundation\Bootstrap\UserAccessPolicy')->bootstrap($app);

        $this->assertEquals(['Guest'], Auth::roles());

        $user = m::mock('\Orchestra\Model\User[getRoles]');
        $user->id = 1;

        $user->shouldReceive('getRoles')->once()->andReturn([
            'Administrator',
        ]);

        $this->assertEquals(
            ['Administrator'],
            Event::until('orchestra.auth: roles', [$user, []])
        );
    }
}
