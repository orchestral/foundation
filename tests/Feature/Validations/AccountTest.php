<?php

namespace Orchestra\Tests\Feature\Validations;

use Orchestra\Foundation\Testing\Installation;
use Orchestra\Foundation\Validations\Account;
use Orchestra\Tests\Feature\TestCase;

class AccountTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_declares_proper_signature()
    {
        $stub = $this->app->make(Account::class);

        $this->assertInstanceOf('\Orchestra\Support\Validator', $stub);
    }

    /** @test */
    public function it_can_validate_account()
    {
        $data = [
            'email' => 'admin@orchestraplatform.com',
            'fullname' => 'Administrator',
        ];

        $stub = $this->app->make(Account::class)->validate($data);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $stub);
        $this->assertTrue($stub->passes());
    }

    /** @test */
    public function it_can_validate_account_on_register()
    {
        $data = [
            'email' => 'admin@orchestraplatform.com',
            'fullname' => 'Administrator',
        ];

        $stub = $this->app->make(Account::class)->state('register')->validate($data);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $stub);
        $this->assertTrue($stub->passes());
    }

    /** @test */
    public function it_can_validate_account_on_password_change()
    {
        $data = [
            'current_password' => 'secret',
            'new_password' => 'qwerty',
            'confirm_password' => 'qwerty',
        ];

        $rules = [
            'current_password' => ['required'],
            'new_password' => ['required', 'different:current_password'],
            'confirm_password' => ['same:new_password'],
        ];

        $stub = $this->app->make(Account::class)->state('changePassword')->validate($data);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $stub);
        $this->assertTrue($stub->passes());
    }
}
