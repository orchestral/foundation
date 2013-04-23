<?php namespace Orchestra\Services\Validation;

use Illuminate\Support\Facades\Event,
	Illuminate\Support\Facades\Validator as V;

abstract class Validator {
	
	/**
	 * List of rules.
	 *
	 * @var array
	 */
	protected static $rules = array();

	/**
	 * Event listener for current service.
	 *
	 * @var string
	 */
	protected $event = null;

	/**
	 * Validation result.
	 *
	 * @var Illuminate\Validation\Validator
	 */
	protected $validation = null;

	/**
	 * Construct a new validation service.
	 *
	 * @access public
	 * @param  string   $event
	 * @return void
	 */
	public function __construct($event)
	{
		if (is_string($event)) $this->event = $event;
	}

	/**
	 * Make a new validation service.
	 *
	 * @access public
	 * @return void
	 */
	public function make($input)
	{
		$rules = static::$rules;

		if ( ! is_null($this->event))
		{
			Event::fire($this->event, array( & $rules));
		}

		$this->validation = V::make($input, $rules);
	}

	/**
	 * Get the validation errors.
	 *
	 * @access public
	 * @return array
	 */
	public function get()
	{
		return $this->validation;
	}

	/**
	 * Magic method to access Illuminate\Validation\Validator
	 */
	public function __call($method, $parameters)
	{
		return call_user_func_array(array($this->validation, $method), $parameters);
	}
}