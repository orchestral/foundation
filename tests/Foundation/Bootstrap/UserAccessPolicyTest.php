<?php namespace Orchestra\Foundation\Bootstrap\TestCase;

use Mockery as m;
use Orchestra\Testing\TestCase;

class UserAccessPolicyTest extends TestCase
{
    /**
     * Test Orchestra\Foundation\Bootstrap\UserAccessPolicy::bootstrap()
     * method.
     *
     * @test
     */
    public function testBootstrapMethod()
    {
        $this->app->make('Orchestra\Foundation\Bootstrap\UserAccessPolicy')->bootstrap($this->app);

        $this->assertEquals(['Guest'], $this->app['auth']->roles());

        $user = m::mock('\Orchestra\Model\User[getRoles]');
        $user->id = 1;

        $user->shouldReceive('getRoles')->once()->andReturn([
            'Administrator',
        ]);

        $this->assertEquals(
            ['Administrator'],
            $this->app['events']->until('orchestra.auth: roles', [$user, []])
        );
    }
}
