<?php

namespace Orchestra\Tests\Feature\Validation;

use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Validation\Account;
use Orchestra\Foundation\Testing\Installation;

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

        $stub = $this->app->make(Account::class)->with($data);

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

        $stub = $this->app->make(Account::class)->on('register')->with($data);

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

        $stub = $this->app->make(Account::class)->on('changePassword')->with($data);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $stub);
        $this->assertTrue($stub->passes());
    }
}
