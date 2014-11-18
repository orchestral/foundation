<?php namespace Orchestra\Foundation\Tests\Validation;

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
        $events = m::mock('\Illuminate\Contracts\Events\Dispatcher');
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
        $events = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = array(
            'site_name'     => 'Orchestra Platform',
            'email_address' => 'admin@orchestraplatform.com',
            'email_driver'  => 'mail',
            'email_port'    => 25,
        );

        $rules = array(
            'site_name'     => array('required'),
            'email_address' => array('required', 'email'),
            'email_driver'  => array('required', 'in:mail,smtp,sendmail,mailgun,mandrill'),
            'email_port'    => array('numeric'),
        );

        $factory->shouldReceive('make')->once()->with($input, $rules, array())->andReturn($validator);
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
        $events = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = array(
            'site_name'      => 'Orchestra Platform',
            'email_address'  => 'admin@orchestraplatform.com',
            'email_driver'   => 'smtp',
            'email_port'     => 25,
            'email_username' => 'admin@orchestraplatform.com',
            'email_password' => '123456',
        );

        $rules = array(
            'site_name'      => array('required'),
            'email_address'  => array('required', 'email'),
            'email_driver'   => array('required', 'in:mail,smtp,sendmail,mailgun,mandrill'),
            'email_port'     => array('numeric'),
            'email_username' => array('required'),
            'email_host'     => array('required'),
        );

        $factory->shouldReceive('make')->once()->with($input, $rules, array())->andReturn($validator);
        $events->shouldReceive('fire')->once()->with('orchestra.validate: settings', m::any())->andReturnNull();

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
        $events = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = array(
            'site_name'      => 'Orchestra Platform',
            'email_address'  => 'admin@orchestraplatform.com',
            'email_driver'   => 'sendmail',
            'email_port'     => 25,
            'email_sendmail' => '/usr/bin/sendmail -t',
        );

        $rules = array(
            'site_name'      => array('required'),
            'email_address'  => array('required', 'email'),
            'email_driver'   => array('required', 'in:mail,smtp,sendmail,mailgun,mandrill'),
            'email_port'     => array('numeric'),
            'email_sendmail' => array('required'),
        );

        $factory->shouldReceive('make')->once()->with($input, $rules, array())->andReturn($validator);
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
        $events = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = array(
            'site_name'     => 'Orchestra Platform',
            'email_address' => 'admin@orchestraplatform.com',
            'email_driver'  => 'mailgun',
            'email_port'     => 25,
            'email_secret'  => 'auniquetoken',
            'email_domain'  => 'orchestraplatform.com',
        );

        $rules = array(
            'site_name'     => array('required'),
            'email_address' => array('required', 'email'),
            'email_driver'  => array('required', 'in:mail,smtp,sendmail,mailgun,mandrill'),
            'email_port'     => array('numeric'),
            'email_secret'  => array('required'),
            'email_domain'  => array('required'),
        );

        $factory->shouldReceive('make')->once()->with($input, $rules, array())->andReturn($validator);
        $events->shouldReceive('fire')->once()->with('orchestra.validate: settings', m::any())->andReturnNull();

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
        $events = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = array(
            'site_name'     => 'Orchestra Platform',
            'email_address' => 'admin@orchestraplatform.com',
            'email_driver'  => 'mandrill',
            'email_port'    => 25,
            'email_secret'  => 'auniquetoken',
        );

        $rules = array(
            'site_name'     => array('required'),
            'email_address' => array('required', 'email'),
            'email_driver'  => array('required', 'in:mail,smtp,sendmail,mailgun,mandrill'),
            'email_port'    => array('numeric'),
            'email_secret'  => array('required'),
        );

        $factory->shouldReceive('make')->once()->with($input, $rules, array())->andReturn($validator);
        $events->shouldReceive('fire')->once()->with('orchestra.validate: settings', m::any())->andReturnNull();

        $stub       = new Setting($factory, $events);
        $validation = $stub->on('mandrill')->with($input);

        $this->assertEquals($validator, $validation);
    }
}
