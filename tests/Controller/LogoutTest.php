<?php

namespace Orchestra\Tests\Controller;

use Illuminate\Support\Facades\Route;
use Orchestra\Foundation\Testing\Installation;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class LogoutTest extends TestCase
{
    use Installation,
        WithoutMiddleware;

    /** @test */
    public function it_can_logout_an_admin()
    {
        $this->actingAs($this->adminUser)
            ->makeRequest('POST', 'admin/logout', ['_method' => 'DELETE'])
            ->seePageIs('admin/login');

        $this->dontSeeIsAuthenticated();
    }

    /** @test */
    public function it_can_logout_an_admin_with_redirection()
    {
        Route::get('welcome', function () {
            return 'Hello world';
        });

        $this->actingAs($this->adminUser)
            ->makeRequest('POST', 'admin/logout', ['_method' => 'DELETE', 'redirect' => 'welcome'])
            ->seePageIs('welcome')
            ->seeText('Hello world');

        $this->dontSeeIsAuthenticated();
    }
}
