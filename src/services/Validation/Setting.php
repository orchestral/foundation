<?php namespace Orchestra\Services\Validation;

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

	protected $events = array(
		'orchestra.validate: settings',
	);

	protected function onSmtpDriver()
	{
		static::$rules['email_username'] = array('required', 'email');
		static::$rules['email_host']     = array('required');
	}
}