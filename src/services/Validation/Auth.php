<?php namespace Orchestra\Services\Validation;

class Auth extends Validator {
	
	/**
	 * List of rules.
	 *
	 * @var array
	 */
	protected static $rules = array(
		'username' => array('required'),
		'password' => array('required'),
	);
	
}