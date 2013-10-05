<?php namespace Orchestra\Foundation\Validation;

use Orchestra\Support\Validator;

class Auth extends Validator {
	
	/**
	 * List of rules.
	 *
	 * @var array
	 */
	protected $rules = array(
		'email' => array('required', 'email'),
	);

	/**
	 * On login scenario.
	 *
	 * @return void
	 */
	protected function onLogin()
	{
		$this->rules['password'] = array('required');
	}
}
