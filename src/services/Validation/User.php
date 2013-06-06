<?php namespace Orchestra\Services\Validation;

use Orchestra\Support\Validator;

class User extends Validator {
	
	/**
	 * List of rules.
	 *
	 * @var array
	 */
	protected $rules = array(
		'email'    => array('required', 'email'),
		'fullname' => array('required'),
		'roles'    => array('required'),
	);

	/**
	 * List of events.
	 *
	 * @var array
	 */
	protected $events = array(
		'orchestra.validate: users', 
		'orchestra.validate: user.account',
	);

	/**
	 * On create user scenario.
	 *
	 * @access protected
	 * @return void
	 */
	protected function onCreate()
	{
		$this->rules['password'] = array('required');
	}
}
