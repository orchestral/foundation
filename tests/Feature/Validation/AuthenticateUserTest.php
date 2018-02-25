<?php

namespace Orchestra\Tests\Feature\Validation;

use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Validation\AuthenticateUser;

class AuthenticateUserTest extends TestCase
{
    /** @test */
    public function it_declares_proper_signature()
    {
        $stub = $this->app->make(AuthenticateUser::class);

        $this->assertInstanceOf('\Orchestra\Support\Validator', $stub);
    }

    /**
     * Test Orchestra\Foundation\Validation\Auth validation.
     *
     * @test
     */
    public function testValidation()
    {
        $data = [
            'email' => 'admin@orchestraplatform.com',
            'password' => '123',
        ];

        $stub = $this->app->make(AuthenticateUser::class)->with($data);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $stub);
        $this->assertTrue($stub->passes());
    }

    /** @test */
    public function it_can_validate_on_login()
    {
        $data = [
            'username' => 'admin@orchestraplatform.com',
            'password' => '123',
        ];

        $stub = $this->app->make(AuthenticateUser::class)->on('login')->with($data);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $stub);
        $this->assertTrue($stub->passes());
    }
}
