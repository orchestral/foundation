<?php

namespace Orchestra\Tests\Feature\Http\Controllers;

use Orchestra\Foundation\Testing\Installation;
use Orchestra\Tests\Feature\TestCase;

class DashboardControllerTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_show_missing_page()
    {
        $user = $this->createUserAsMember();

        $this->actingAs($user)
            ->call('GET', 'admin/missing')
            ->assertStatus(404)
            ->assertSeeText('Not Found');
    }
}
