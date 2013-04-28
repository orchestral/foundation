<?php namespace Orchestra\Services\Validation;

use Orchestra\Support\Validator;

class UserAccount extends Validator {
	
	/**
	 * List of rules.
	 *
	 * @var array
	 */
	protected static $rules = array(
		'email'    => array('required', 'email'),
		'fullname' => array('required'),
	);
	
	/**
	 * List of events.
	 *
	 * @var array
	 */
	protected static $events = array(
		'orchestra.validate: user.account',
	);

	/**
	 * On register scenario.
	 *
	 * @access protected
	 * @return void
	 */
	protected function onRegister() 
	{
		static::$rules['email'] = array('required', 'email', 'unique:users,email');
	}

	/**
	 * On update password scenario.
	 *
	 * @access protected
	 * @return void
	 */
	protected function onChangePassword()
	{
		static::$rules = array(
			'current_password' => array('required'),
			'new_password'     => array('required', 'different:current_password'),
			'confirm_password' => array('same:new_password'),
		);

		static::$events = array();
	}
}
