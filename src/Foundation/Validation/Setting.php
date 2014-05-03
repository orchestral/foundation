<?php namespace Orchestra\Foundation\Validation;

use Orchestra\Support\Validator;

class Setting extends Validator
{
    /**
     * List of rules.
     *
     * @var array
     */
    protected $rules = array(
        'site_name'     => array('required'),
        'email_address' => array('required', 'email'),
        'email_driver'  => array('required', 'in:mail,smtp,sendmail,mailgun,mandrill'),
        'email_port'    => array('numeric'),
    );

    /**
     * List of events.
     *
     * @var array
     */
    protected $events = array(
        'orchestra.validate: settings',
    );

    /**
     * On update email using smtp driver scenario.
     *
     * @return void
     */
    protected function onSmtp()
    {
        $this->rules['email_username'] = array('required');
        $this->rules['email_host']     = array('required');
    }

    /**
     * On update email using sendmail driver scenario.
     *
     * @return void
     */
    protected function onSendmail()
    {
        $this->rules['email_sendmail'] = array('required');
    }

    /**
     * On update email using mailgun driver scenario.
     *
     * @return void
     */
    protected function onMailgun()
    {
        $this->rules['email_secret'] = array('required');
        $this->rules['email_domain'] = array('required');
    }

    /**
     * On update email using mandrill driver scenario.
     *
     * @return void
     */
    protected function onMandrill()
    {
        $this->rules['email_secret'] = array('required');
    }
}
