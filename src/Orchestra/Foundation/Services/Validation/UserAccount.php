<?php namespace Orchestra\Foundation\Services\Validation;

use Illuminate\Support\Fluent;
use Orchestra\Support\Validator;

class UserAccount extends Validator {
	
	/**
	 * List of rules.
	 *
	 * @var array
	 */
	protected $rules = array(
		'email'    => array('required', 'email'),
		'fullname' => array('required'),
	);
	
	/**
	 * List of events.
	 *
	 * @var array
	 */
	protected $events = array(
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
		$this->rules['email'] = array('required', 'email', 'unique:users,email');
	}

	/**
	 * On update password scenario.
	 *
	 * @access protected
	 * @return void
	 */
	protected function onChangePassword()
	{
		$this->setRules(array(
			'current_password' => array('required'),
			'new_password'     => array('required', 'different:current_password'),
			'confirm_password' => array('same:new_password'),
		));

		$this->events = array();
	}
}
