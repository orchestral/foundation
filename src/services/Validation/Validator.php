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
	 * Validation result.
	 *
	 * @var Illuminate\Validation\Validator
	 */
	protected $validation = null;

	/**
	 * Construct a new validation service.
	 *
	 * @access public
	 * @param  array    $input
	 * @param  string   $event
	 * @return void
	 */
	public function with($input, $event = null)
	{
		$rules = static::$rules;

		if ( ! is_null($event))
		{
			Event::fire($event, array( & $rules));
		}

		return V::make($input, $rules);
	}
}