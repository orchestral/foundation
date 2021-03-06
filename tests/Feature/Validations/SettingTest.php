<?php

namespace Orchestra\Tests\Feature\Validations;

use Orchestra\Foundation\Validations\Setting;
use Orchestra\Tests\Feature\TestCase;

class SettingTest extends TestCase
{
    /**
     * @test
     */
    public function it_declares_proper_signature()
    {
        $stub = $this->app->make(Setting::class);

        $this->assertInstanceOf('\Orchestra\Support\Validator', $stub);
    }

    /**
     * Test Orchestra\Foundation\Validations\Setting validation.
     *
     * @test
     */
    public function it_can_validate_settings()
    {
        $data = [
            'site_name' => 'Orchestra Platform',
            'email_address' => 'admin@orchestraplatform.com',
            'email_driver' => 'mail',
            'email_port' => 25,
        ];

        $stub = $this->app->make(Setting::class)->validate($data);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $stub);
        $this->assertTrue($stub->passes());
    }

    /**
     * @test
     */
    public function it_can_validate_for_smtp()
    {
        $data = [
            'site_name' => 'Orchestra Platform',
            'email_address' => 'admin@orchestraplatform.com',
            'email_driver' => 'smtp',
            'email_host' => 'smtp.mailtrap.io',
            'email_port' => 25,
            'email_username' => 'admin@orchestraplatform.com',
            'email_password' => '123456',
        ];

        $stub = $this->app->make(Setting::class)->state('smtp')->validate($data);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $stub);
        $this->assertTrue($stub->passes());
    }

    /**
     * @test
     */
    public function it_can_validate_for_sendmail()
    {
        $data = [
            'site_name' => 'Orchestra Platform',
            'email_address' => 'admin@orchestraplatform.com',
            'email_driver' => 'sendmail',
            'email_port' => 25,
            'email_sendmail' => '/usr/bin/sendmail -t',
        ];

        $stub = $this->app->make(Setting::class)->state('sendmail')->validate($data);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $stub);
        $this->assertTrue($stub->passes());
    }

    /**
     * @test
     */
    public function it_can_validate_for_mailgun()
    {
        $data = [
            'site_name' => 'Orchestra Platform',
            'email_address' => 'admin@orchestraplatform.com',
            'email_driver' => 'mailgun',
            'email_port' => 25,
            'email_secret' => 'auniquetoken',
            'email_domain' => 'orchestraplatform.com',
        ];

        $stub = $this->app->make(Setting::class)->state('mailgun')->validate($data);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $stub);
        $this->assertTrue($stub->passes());
    }

    /**
     * @test
     */
    public function it_can_validate_for_ses()
    {
        $data = [
            'site_name' => 'Orchestra Platform',
            'email_address' => 'admin@orchestraplatform.com',
            'email_driver' => 'ses',
            'email_port' => 25,
            'email_key' => 'auniquekey',
            'email_secret' => 'auniquetoken',
            'email_region' => 'us-east-1',
        ];


        $stub = $this->app->make(Setting::class)->state('ses')->validate($data);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $stub);
        $this->assertTrue($stub->passes());
    }
}
