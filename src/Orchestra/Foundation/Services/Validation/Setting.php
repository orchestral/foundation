<?php namespace Orchestra\Foundation\Services\Validation;

use Orchestra\Support\Validator;

class Setting extends Validator {
	
	/**
	 * List of rules.
	 *
	 * @var array
	 */
	protected $rules = array(
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
	protected $events = array(
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
		$this->rules['email_username'] = array('required');
		$this->rules['email_host']     = array('required');
	}

	/**
	 * On update email using sendmail driver scenario.
	 *
	 * @access protected
	 * @return void
	 */
	protected function onSendmail()
	{
		$this->rules['email_sendmail'] = array('required');
	}
}
