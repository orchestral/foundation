<?php namespace Orchestra\Foundation\Validation;

use Orchestra\Support\Validator;

class Account extends Validator
{
    /**
     * List of rules.
     *
     * @var array
     */
    protected $rules = array(
        'email'    => array('required', 'email'),
        'fullname' => array('required'),
    );

    /**
     * List of events.
     *
     * @var array
     */
    protected $events = array(
        'orchestra.validate: user.account',
    );

    /**
     * On register scenario.
     *
     * @return void
     */
    protected function onRegister()
    {
        $this->rules['email'] = array('required', 'email', 'unique:users,email');
        $this->events[] = 'orchestra.validate: user.registration';
    }

    /**
     * On update password scenario.
     *
     * @return void
     */
    protected function onChangePassword()
    {
        $this->rules = array(
            'current_password' => array('required'),
            'new_password'     => array('required', 'different:current_password'),
            'confirm_password' => array('same:new_password'),
        );

        $this->events = array();
    }
}
