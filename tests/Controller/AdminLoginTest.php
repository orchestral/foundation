<?php

namespace Orchestra\Tests\Controller;

use Orchestra\Foundation\Testing\Installation;

class AdminLoginTest extends TestCase
{
    use Installation;

    /**
     * My test implementation.
     */
    public function testItCantLoginInvalidUser()
    {
        $this->visit('admin/login')
            ->type('hello@orchestraplatform.com', 'username')
            ->type('hello', 'password')
            ->press('Login')
            ->seePageIs('admin/login')
            ->see('Invalid user and password combination.');
    }

    /**
     * My test implementation.
     */
    public function testItLoginValidUser()
    {
        $this->visit('admin/login')
            ->type($this->adminUser->email, 'username')
            ->type('secret', 'password')
            ->press('Login')
            ->seePageIs('admin');
    }
}
