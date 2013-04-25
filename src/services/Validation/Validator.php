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
	 * List of events.
	 *
	 * @var array
	 */
	protected static $events = array();

	/**
	 * Create a scope scenario.
	 *
	 * @access public
	 * @param  string   $scenario
	 * @return self
	 */
	public function on($scenario)
	{
		$method = 'on'.ucfirst($scenario);

		if (method_exists($this, $method)) $this->{$method}();

		return $this;
	}

	/**
	 * Execute validation service.
	 *
	 * @access public
	 * @param  array    $input
	 * @param  string   $event
	 * @return void
	 */
	public function with($input, $events = array())
	{
		$rules = static::$rules;

		$this->runValidationEvents($events);

		return V::make($input, $rules);
	}

	/**
	 * Run validation events.
	 *
	 * @access protected
	 * @return void
	 */
	protected function runValidationEvents($events)
	{
		$events = array_merge(static::$events, (array) $events);

		foreach ((array) $events as $event) 
		{
			Event::fire($event, array( & $rules));
		}
	}
}