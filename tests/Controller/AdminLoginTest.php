<?php

namespace Orchestra\Tests\Controller;

class AdminLoginTest extends TestCase
{
    /**
     * My test implementation.
     */
    public function testItCantLoginInvalidUser()
    {
        $user = $this->install();

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
        $user = $this->install();

        $this->visit('admin/login')
            ->type($user->email, 'username')
            ->type('secret', 'password')
            ->press('Login')
            ->seePageIs('admin');
    }
}
