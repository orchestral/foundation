<?php

namespace Orchestra\Foundation\Validations;

use Orchestra\Support\Validator;

class User extends Validator
{
    /**
     * List of rules.
     *
     * @var array
     */
    protected $rules = [
        'email' => ['required', 'email'],
        'fullname' => ['required'],
        'roles' => ['required'],
    ];

    /**
     * List of events.
     *
     * @var array
     */
    protected $events = [
        'orchestra.validate: users',
        'orchestra.validate: user.account',
    ];

    /**
     * On create user scenario.
     *
     * @return void
     */
    protected function onCreate()
    {
        $this->rules['password'] = ['required'];
    }
}
