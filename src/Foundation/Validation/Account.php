<?php namespace Orchestra\Foundation\Validation;

use Orchestra\Support\Validator;

class Account extends Validator
{
    /**
     * List of rules.
     *
     * @var array
     */
    protected $rules = [
        'email'    => ['required', 'email'],
        'fullname' => ['required'],
    ];

    /**
     * List of events.
     *
     * @var array
     */
    protected $events = [
        'orchestra.validate: user.account',
    ];

    /**
     * On register scenario.
     *
     * @return void
     */
    protected function onRegister()
    {
        $this->rules['email'] = ['required', 'email', 'unique:users,email'];
        $this->events[] = 'orchestra.validate: user.account.register';
        $this->events[] = 'orchestra.validate: user.registration';
    }

    /**
     * On update password scenario.
     *
     * @return void
     */
    protected function onChangePassword()
    {
        $this->rules = [
            'current_password' => ['required'],
            'new_password'     => ['required', 'different:current_password'],
            'confirm_password' => ['same:new_password'],
        ];

        $this->events = [];
    }
}
