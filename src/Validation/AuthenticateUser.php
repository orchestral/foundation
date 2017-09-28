<?php

namespace Orchestra\Foundation\Validation;

use Laravie\Authen\Authen;
use Orchestra\Support\Validator;

class AuthenticateUser extends Validator
{
    /**
     * List of rules.
     *
     * @var array
     */
    protected $rules = [
        'email' => ['sometimes', 'required', 'email'],
        'fullname' => ['sometimes', 'required'],
    ];

    /**
     * On login scenario.
     *
     * @return void
     */
    protected function onLogin()
    {
        $this->rules[Authen::getIdentifierName()] = ['required'];
        $this->rules['password'] = ['required'];
    }
}
