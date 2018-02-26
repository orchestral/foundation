<?php

namespace Orchestra\Tests\Feature\Bootstrap;

use Orchestra\Foundation\Auth\User;
use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Testing\Installation;

class UserAccessPolicyTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_can_boot_for_admin()
    {
        $this->assertEquals(
            collect(['Administrator']),
            $this->app['events']->until('orchestra.auth: roles', [$this->adminUser, []])
        );
    }

    /** @test */
    public function it_can_boot_for_member()
    {
        $user = $this->createUserAsMember();

        $this->assertEquals(
            collect(['Member']),
            $this->app['events']->until('orchestra.auth: roles', [$user, []])
        );
    }

    /** @test */
    public function it_can_boot_for_user_with_multiple_roles()
    {
        $user = User::faker()->create();
        $user->attachRole([1, 2]);

        $this->assertEquals(
            collect(['Administrator', 'Member']),
            $this->app['events']->until('orchestra.auth: roles', [$user, []])
        );
    }
}
