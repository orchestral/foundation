<?php namespace Orchestra\Services\Validation;

use Orchestra\Support\Validator;

class Auth extends Validator {
	
	/**
	 * List of rules.
	 *
	 * @var array
	 */
	protected static $rules = array(
		'email' => array('required', 'email'),
	);

	/**
	 * On login scenario.
	 *
	 * @access protected
	 * @return void
	 */
	protected function onLogin()
	{
		static::$rules['password'] = array('required');
	}
}
