<?php

namespace Orchestra\Tests\Feature\Http\Controllers;

use Orchestra\Foundation\Auth\User;
use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Testing\Installation;

class DashboardControllerTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_show_missing_page()
    {
        $user = User::faker()->create();
        $user->attachRole([2]);

        $this->actingAs($user)
            ->call('GET', 'admin/missing')
            ->assertStatus(404)
            ->assertSeeText('Sorry, the page you are looking for could not be found.');
    }
}
