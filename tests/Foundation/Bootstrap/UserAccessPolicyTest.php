<?php

namespace Orchestra\Foundation\TestCase\Bootstrap;

use Mockery as m;
use Orchestra\Testing\TestCase;
use Illuminate\Support\Collection;

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

        $this->assertEquals(new Collection(['Guest']), $this->app['auth']->roles());

        $user = m::mock('\Orchestra\Model\User[getRoles]');
        $user->id = 1;

        $user->shouldReceive('getRoles')->once()->andReturn(['Administrator']);

        $this->assertEquals(
            ['Administrator'],
            $this->app['events']->until('orchestra.auth: roles', [$user, []])
        );
    }
}
