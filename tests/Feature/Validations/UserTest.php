<?php

namespace Orchestra\Tests\Feature\Validation;

use Orchestra\Foundation\Validations\User;
use Orchestra\Tests\Feature\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function it_declares_proper_signature()
    {
        $stub = $this->app->make(User::class);

        $this->assertInstanceOf('\Orchestra\Support\Validator', $stub);
    }

    /** @test */
    public function it_can_validate_user()
    {
        $data = [
            'email' => 'admin@orchestraplatform.com',
            'fullname' => 'Administrator',
            'roles' => 1,
        ];

        $stub = $this->app->make(User::class)->validate($data);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $stub);
        $this->assertTrue($stub->passes());
    }

    /** @test */
    public function it_can_validate_user_on_create()
    {
        $data = [
            'email' => 'admin@orchestraplatform.com',
            'fullname' => 'Administrator',
            'roles' => 1,
            'password' => '123456',
        ];

        $stub = $this->app->make(User::class)->state('create')->validate($data);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $stub);
        $this->assertTrue($stub->passes());
    }
}
