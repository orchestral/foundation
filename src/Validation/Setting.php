<?php namespace Orchestra\Foundation\Validation;

use Orchestra\Support\Validator;

class Setting extends Validator
{
    /**
     * List of rules.
     *
     * @var array
     */
    protected $rules = [
        'site_name'     => ['required'],
        'email_address' => ['required', 'email'],
        'email_driver'  => ['required', 'in:mail,smtp,sendmail,ses,mailgun,mandrill'],
        'email_port'    => ['numeric'],
    ];

    /**
     * List of events.
     *
     * @var array
     */
    protected $events = [
        'orchestra.validate: settings',
    ];

    /**
     * On update email using smtp driver scenario.
     *
     * @return void
     */
    protected function onSmtp()
    {
        $this->rules['email_username'] = ['required'];
        $this->rules['email_host']     = ['required'];
    }

    /**
     * On update email using sendmail driver scenario.
     *
     * @return void
     */
    protected function onSendmail()
    {
        $this->rules['email_sendmail'] = ['required'];
    }

    /**
     * On update email using mailgun driver scenario.
     *
     * @return void
     */
    protected function onMailgun()
    {
        $this->rules['email_secret'] = ['required'];
        $this->rules['email_domain'] = ['required'];
    }

    /**
     * On update email using mandrill driver scenario.
     *
     * @return void
     */
    protected function onMandrill()
    {
        $this->rules['email_secret'] = ['required'];
    }

    /**
     * On update email using SES driver scenario.
     *
     * @return void
     */
    protected function onSes()
    {
        $this->rules['email_key'] = ['required'];
        $this->rules['email_secret'] = ['required'];
        $this->rules['email_region'] = ['required', 'in:us-east-1,us-west-2,eu-west-1'];
    }
}
