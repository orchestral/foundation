<?php namespace Orchestra\Foundation\Tests\Validation;

use Illuminate\Support\Fluent;
use Mockery as m;
use Orchestra\Foundation\Validation\Setting;

class SettingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Validation\Setting.
     *
     * @test
     */
    public function testInstance()
    {
        $events  = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory = m::mock('\Illuminate\Contracts\Validation\Factory');

        $stub = new Setting($factory, $events);

        $this->assertInstanceOf('\Orchestra\Support\Validator', $stub);
    }

    /**
     * Test Orchestra\Foundation\Validation\Setting validation.
     *
     * @test
     */
    public function testValidation()
    {
        $events    = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory   = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = [
            'site_name'     => 'Orchestra Platform',
            'email_address' => 'admin@orchestraplatform.com',
            'email_driver'  => 'mail',
            'email_port'    => 25,
        ];

        $rules = [
            'site_name'     => ['required'],
            'email_address' => ['required', 'email'],
            'email_driver'  => ['required', 'in:mail,smtp,sendmail,ses,mailgun,mandrill'],
            'email_port'    => ['numeric'],
        ];

        $factory->shouldReceive('make')->once()->with($input, $rules, [])->andReturn($validator);
        $events->shouldReceive('fire')->once()->with('orchestra.validate: settings', m::any())->andReturnNull();

        $stub       = new Setting($factory, $events);
        $validation = $stub->with($input);

        $this->assertEquals($validator, $validation);
    }

    /**
     * Test Orchestra\Foundation\Validation\Setting on stmp
     * setting.
     *
     * @test
     */
    public function testValidationOnSmtp()
    {
        $events    = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory   = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = [
            'site_name'      => 'Orchestra Platform',
            'email_address'  => 'admin@orchestraplatform.com',
            'email_driver'   => 'smtp',
            'email_port'     => 25,
            'email_username' => 'admin@orchestraplatform.com',
            'email_password' => '123456',
        ];

        $rules = [
            'site_name'      => ['required'],
            'email_address'  => ['required', 'email'],
            'email_driver'   => ['required', 'in:mail,smtp,sendmail,ses,mailgun,mandrill'],
            'email_port'     => ['numeric'],
            'email_username' => ['required'],
            'email_host'     => ['required'],
        ];

        $factory->shouldReceive('make')->once()->with($input, $rules, [])->andReturn($validator);
        $events->shouldReceive('fire')->once()->with('orchestra.validate: settings', m::any())->andReturnNull();
        $validator->shouldReceive('sometimes')->once()
            ->with('email_password', 'required', m::type('Closure'))
            ->andReturnUsing(function ($f, $r, $c) {
                $i = new Fluent(['enable_change_password' => 'yes', 'email_password' => '123456']);

                return $c($i);
            });

        $stub       = new Setting($factory, $events);
        $validation = $stub->on('smtp')->with($input);

        $this->assertEquals($validator, $validation);
    }

    /**
     * Test Orchestra\Foundation\Validation\Setting on sendmail
     * setting.
     *
     * @test
     */
    public function testValidationOnSendmail()
    {
        $events    = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory   = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = [
            'site_name'      => 'Orchestra Platform',
            'email_address'  => 'admin@orchestraplatform.com',
            'email_driver'   => 'sendmail',
            'email_port'     => 25,
            'email_sendmail' => '/usr/bin/sendmail -t',
        ];

        $rules = [
            'site_name'      => ['required'],
            'email_address'  => ['required', 'email'],
            'email_driver'   => ['required', 'in:mail,smtp,sendmail,ses,mailgun,mandrill'],
            'email_port'     => ['numeric'],
            'email_sendmail' => ['required'],
        ];

        $factory->shouldReceive('make')->once()->with($input, $rules, [])->andReturn($validator);
        $events->shouldReceive('fire')->once()->with('orchestra.validate: settings', m::any())->andReturnNull();

        $stub       = new Setting($factory, $events);
        $validation = $stub->on('sendmail')->with($input);

        $this->assertEquals($validator, $validation);
    }

    /**
     * Test Orchestra\Foundation\Validation\Setting on mailgun
     * setting.
     *
     * @test
     */
    public function testValidationOnMailgun()
    {
        $events    = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory   = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = [
            'site_name'      => 'Orchestra Platform',
            'email_address'  => 'admin@orchestraplatform.com',
            'email_driver'   => 'mailgun',
            'email_port'     => 25,
            'email_secret'   => 'auniquetoken',
            'email_domain'   => 'orchestraplatform.com',
        ];

        $rules = [
            'site_name'      => ['required'],
            'email_address'  => ['required', 'email'],
            'email_driver'   => ['required', 'in:mail,smtp,sendmail,ses,mailgun,mandrill'],
            'email_port'     => ['numeric'],
            'email_domain'   => ['required'],
        ];

        $factory->shouldReceive('make')->once()->with($input, $rules, [])->andReturn($validator);
        $events->shouldReceive('fire')->once()->with('orchestra.validate: settings', m::any())->andReturnNull();
        $validator->shouldReceive('sometimes')->once()
            ->with('email_secret', 'required', m::type('Closure'))
            ->andReturnUsing(function ($f, $r, $c) {
                $i = new Fluent(['enable_change_secret' => 'yes', 'email_secret' => 'auniquetoken']);

                return $c($i);
            });

        $stub       = new Setting($factory, $events);
        $validation = $stub->on('mailgun')->with($input);

        $this->assertEquals($validator, $validation);
    }

    /**
     * Test Orchestra\Foundation\Validation\Setting on mandrill
     * setting.
     *
     * @test
     */
    public function testValidationOnMandrill()
    {
        $events    = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory   = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = [
            'site_name'     => 'Orchestra Platform',
            'email_address' => 'admin@orchestraplatform.com',
            'email_driver'  => 'mandrill',
            'email_port'    => 25,
            'email_secret'  => 'auniquetoken',
        ];

        $rules = [
            'site_name'     => ['required'],
            'email_address' => ['required', 'email'],
            'email_driver'  => ['required', 'in:mail,smtp,sendmail,ses,mailgun,mandrill'],
            'email_port'    => ['numeric'],
        ];

        $factory->shouldReceive('make')->once()->with($input, $rules, [])->andReturn($validator);
        $events->shouldReceive('fire')->once()->with('orchestra.validate: settings', m::any())->andReturnNull();
        $validator->shouldReceive('sometimes')->once()
            ->with('email_secret', 'required', m::type('Closure'))
            ->andReturnUsing(function ($f, $r, $c) {
                $i = new Fluent(['enable_change_secret' => 'yes', 'email_secret' => 'auniquetoken']);

                return $c($i);
            });

        $stub       = new Setting($factory, $events);
        $validation = $stub->on('mandrill')->with($input);

        $this->assertEquals($validator, $validation);
    }

    /**
     * Test Orchestra\Foundation\Validation\Setting on SES
     * setting.
     *
     * @test
     */
    public function testValidationOnSes()
    {
        $events    = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory   = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = [
            'site_name'     => 'Orchestra Platform',
            'email_address' => 'admin@orchestraplatform.com',
            'email_driver'  => 'ses',
            'email_port'    => 25,
            'email_key'     => 'auniquekey',
            'email_secret'  => 'auniquetoken',
            'email_region'  => 'us-east-1',
        ];

        $rules = [
            'site_name'     => ['required'],
            'email_address' => ['required', 'email'],
            'email_driver'  => ['required', 'in:mail,smtp,sendmail,ses,mailgun,mandrill'],
            'email_port'    => ['numeric'],
            'email_key'     => ['required'],
            'email_region'  => ['required', 'in:us-east-1,us-west-2,eu-west-1'],
        ];

        $factory->shouldReceive('make')->once()->with($input, $rules, [])->andReturn($validator);
        $events->shouldReceive('fire')->once()->with('orchestra.validate: settings', m::any())->andReturnNull();
        $validator->shouldReceive('sometimes')->once()
            ->with('email_secret', 'required', m::type('Closure'))
            ->andReturnUsing(function ($f, $r, $c) {
                $i = new Fluent(['enable_change_secret' => 'yes', 'email_secret' => 'auniquetoken']);

                return $c($i);
            });

        $stub       = new Setting($factory, $events);
        $validation = $stub->on('ses')->with($input);

        $this->assertEquals($validator, $validation);
    }
}
