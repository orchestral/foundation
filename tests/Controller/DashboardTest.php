<?php

namespace Orchestra\Tests\Controller;

use Orchestra\Foundation\Testing\Installation;

class DashboardTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_can_show_the_dashboard()
    {
        $user = $this->createUserAsMember();

        $this->actingAs($user)
            ->visit('admin')
            ->seeText('Home')
            ->seeText('Welcome to your new Orchestra Platform site!');
    }

    /** @test */
    public function it_can_show_user_nav()
    {
        $user = $this->createUserAsMember();

        $this->actingAs($user)
            ->visit('admin')
            ->seeText($user->fullname)
            ->seeText('Member');
    }

    /** @test */
    public function it_can_show_admin_nav()
    {
        $this->actingAs($this->adminUser)
            ->visit('admin')
            ->seeText($this->adminUser->fullname)
            ->seeText('Administrator');
    }
}
