<?php

namespace Orchestra\Tests\Controller;

use Orchestra\Foundation\Auth\User;
use Orchestra\Foundation\Testing\Installation;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class DashboardTest extends TestCase
{
    use Installation,
        WithoutMiddleware;

    /** @test */
    public function it_can_show_the_dashboard()
    {
        $user = User::faker()->create();
        $user->attachRole([2]);

        $this->actingAs($user)
            ->visit('admin')
            ->seeText('Home');
    }
}
