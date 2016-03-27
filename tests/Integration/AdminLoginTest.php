<?php

namespace Integration\TestCase;

class AdminLoginTest extends TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * My test implementation
     */
    public function testItCantLoginInvalidUser()
    {
        $user = $this->createAdminUser();

        $this->visit('admin/login')
            ->type('hello@orchestraplatform.com', 'email')
            ->type('hello', 'password')
            ->press('Login')
            ->seePageIs('admin/login')
            ->see('Invalid user and password combination.');
    }
}
