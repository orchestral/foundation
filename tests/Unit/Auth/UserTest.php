<?php

namespace Orchestra\Tests\Unit\Auth;

use Orchestra\Foundation\Auth\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function it_can_get_email_for_password_reset()
    {
        $stub = new User();
        $stub->email = 'admin@orchestraplatform.com';

        $this->assertEquals('admin@orchestraplatform.com', $stub->getEmailForPasswordReset());
    }

    /** @test */
    public function it_can_get_recipient_email()
    {
        $stub = new User();
        $stub->email = 'admin@orchestraplatform.com';

        $this->assertEquals('admin@orchestraplatform.com', $stub->getRecipientEmail());
    }

    /** @test */
    public function it_can_get_recipient_name()
    {
        $stub = new User();
        $stub->fullname = 'Administrator';

        $this->assertEquals('Administrator', $stub->getRecipientName());
    }
}
