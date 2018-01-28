<?php

namespace Orchestra\Tests\Unit\Auth;

use PHPUnit\Framework\TestCase;
use Orchestra\Foundation\Auth\User;

class UserTest extends TestCase
{
    /**
     * Test Orchestra\Foundation\Auth\User::getEmailForPasswordReset() method.
     *
     * @test
     */
    public function testGetEmailForPasswordResetMethod()
    {
        $stub = new User();
        $stub->email = 'admin@orchestraplatform.com';

        $this->assertEquals('admin@orchestraplatform.com', $stub->getEmailForPasswordReset());
    }

    /**
     * Test Orchestra\Foundation\Auth\User::getRecipientEmail() method.
     *
     * @test
     */
    public function testGetRecipientEmailMethod()
    {
        $stub = new User();
        $stub->email = 'admin@orchestraplatform.com';

        $this->assertEquals('admin@orchestraplatform.com', $stub->getRecipientEmail());
    }

    /**
     * Test Orchestra\Foundation\Auth\User::getRecipientName() method.
     *
     * @test
     */
    public function testGetRecipientNameMethod()
    {
        $stub = new User();
        $stub->fullname = 'Administrator';

        $this->assertEquals('Administrator', $stub->getRecipientName());
    }
}
