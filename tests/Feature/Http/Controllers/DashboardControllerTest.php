<?php

namespace Orchestra\Tests\Feature\Http\Controllers;

use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Testing\Installation;

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
