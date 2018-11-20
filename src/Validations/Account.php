<?php

namespace Orchestra\Foundation\Validations;

use Orchestra\Support\Validator;

class Account extends Validator
{
    /**
     * List of rules.
     *
     * @var array
     */
    protected $rules = [
        'email' => ['required', 'email'],
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
        $this->rules = array_replace($this->rules, [
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['sometimes', 'required'],
            'password_confirmation' => ['sometimes', 'same:password'],
        ]);

        $this->events[] = 'orchestra.validate: user.account.register';
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
            'new_password' => ['required', 'different:current_password'],
            'confirm_password' => ['same:new_password'],
        ];

        $this->events = [];
    }

    /**
     * On reauthenticate password scenario.
     *
     * @return void
     */
    protected function onReauthenticate()
    {
        $this->rules = [
            'password' => ['required'],
        ];

        $this->events = [];
    }
}
