<?php namespace Orchestra\Services\Validation;

class Auth extends Validator {
	
	/**
	 * List of rules.
	 *
	 * @var array
	 */
	protected static $rules = array(
		'username' => array('required', 'email'),
		'password' => array('required'),
	);
}