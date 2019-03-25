<?php

namespace Orchestra\Tests\Unit\Tools;

use PHPUnit\Framework\TestCase;
use Orchestra\Foundation\Tools\GenerateRandomPassword;

class GenerateRandomPasswordTest extends TestCase
{
    /** @test */
    public function it_can_generate_random_password()
    {
        $password = (new GenerateRandomPassword())(10);

        $this->assertTrue(strlen($password) === 10);
    }

    /** @test */
    public function it_can_generate_random_password_using_default_length()
    {
        $password = (new GenerateRandomPassword())();

        $this->assertTrue(strlen($password) === 6);
    }
}
