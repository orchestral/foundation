<?php

namespace Orchestra\Tests\Controller;

use Orchestra\Foundation\Testing\Installation;

class LoginTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_can_login_an_admin()
    {
        $this->visit('admin/login')
            ->type($this->adminUser->email, 'username')
            ->type('secret', 'password')
            ->press('Login')
            ->seePageIs('admin')
            ->seeText('You have been logged in.');
    }

    /** @test */
    public function it_cant_login_an_invalid_admin()
    {
        $this->visit('admin/login')
            ->type('hello@orchestraplatform.com', 'username')
            ->type('hello', 'password')
            ->press('Login')
            ->seePageIs('admin/login')
            ->see('Invalid user and password combination.');
    }
}
