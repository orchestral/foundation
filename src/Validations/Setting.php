<?php

namespace Orchestra\Foundation\Validations;

use Orchestra\Support\Validator;
use Illuminate\Contracts\Validation\Validator as ValidatorResolver;

class Setting extends Validator
{
    /**
     * List of rules.
     *
     * @var array
     */
    protected $rules = [
        'site_name' => ['required'],
        'email_address' => ['required', 'email'],
        'email_driver' => ['required', 'in:mail,smtp,sendmail,ses,mailgun,mandrill,sparkpost'],
        'email_port' => ['numeric'],
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
        $this->rules['email_host'] = ['required'];
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
        $this->rules['email_domain'] = ['required'];
    }

    /**
     * On update email using SES driver scenario.
     *
     * @return void
     */
    protected function onSes()
    {
        $this->rules['email_key'] = ['required'];
        $this->rules['email_region'] = ['required', 'in:us-east-1,us-west-2,eu-west-1'];
    }

    /**
     * Extend on update email using smtp driver scenario.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $resolver
     *
     * @return void
     */
    protected function extendSmtp(ValidatorResolver $resolver)
    {
        $this->addRequiredForSecretField($resolver, 'email_password', 'enable_change_password');
    }

    /**
     * Extend on update email using mailgun driver scenario.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $resolver
     *
     * @return void
     */
    protected function extendMailgun(ValidatorResolver $resolver)
    {
        $this->addRequiredForSecretField($resolver, 'email_secret', 'enable_change_secret');
    }

    /**
     * Extend on update email using mandrill driver scenario.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $resolver
     *
     * @return void
     */
    protected function extendMandrill(ValidatorResolver $resolver)
    {
        $this->addRequiredForSecretField($resolver, 'email_secret', 'enable_change_secret');
    }

    /**
     * Extend on update email using SES driver scenario.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $resolver
     *
     * @return void
     */
    protected function extendSes(ValidatorResolver $resolver)
    {
        $this->addRequiredForSecretField($resolver, 'email_secret', 'enable_change_secret');
    }

    /**
     * Add required for secret or password field.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $resolver
     * @param  string  $field
     * @param  string  $hidden
     *
     * @return void
     */
    protected function addRequiredForSecretField(ValidatorResolver $resolver, $field, $hidden)
    {
        $resolver->sometimes($field, 'required', function ($input) use ($hidden) {
            return $input->$hidden == 'yes';
        });
    }
}
