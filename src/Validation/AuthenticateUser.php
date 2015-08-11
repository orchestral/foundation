<?php namespace Orchestra\Foundation\Validation;

use Orchestra\Support\Validator;

class AuthenticateUser extends Validator
{
    /**
     * List of rules.
     *
     * @var array
     */
    protected $rules = [
        'email' => ['required', 'email'],
        'fullname' => ['sometimes', 'required'],
    ];

    /**
     * On login scenario.
     *
     * @return void
     */
    protected function onLogin()
    {
        $this->rules['password'] = ['required'];
    }
}
