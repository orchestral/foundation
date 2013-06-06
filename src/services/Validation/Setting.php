<?php namespace Orchestra\Services\Validation;

use Orchestra\Support\Validator;

class Setting extends Validator {
	
	/**
	 * List of rules.
	 *
	 * @var array
	 */
	protected static $rules = array(
		'site_name'     => array('required'),
		'email_address' => array('required', 'email'),
		'email_driver'  => array('required', 'in:mail,smtp,sendmail'),
		'email_port'    => array('numeric'),
	);

	/**
	 * List of events.
	 *
	 * @var array
	 */
	protected static $events = array(
		'orchestra.validate: settings',
	);

	/**
	 * On update email using smtp driver scenario.
	 *
	 * @access protected
	 * @return void
	 */
	protected function onSmtp()
	{
		unset(static::$rules['email_sendmail']);
		static::$rules['email_username'] = array('required');
		static::$rules['email_host']     = array('required');
	}

	/**
	 * On update email using sendmail driver scenario.
	 *
	 * @access protected
	 * @return void
	 */
	protected function onSendmail()
	{
		unset(static::$rules['email_username']);
		unset(static::$rules['email_host']);
		static::$rules['email_sendmail'] = array('required');
	}
}
