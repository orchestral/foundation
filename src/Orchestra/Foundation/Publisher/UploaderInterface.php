<?php namespace Orchestra\Foundation\Publisher;

interface UploaderInterface {

	/**
	 * Get service connection instance.
	 *
	 * @access public
	 * @return Object
	 */
	public function connection();

	/**
	 * Connect to the service.
	 *
	 * @access public	
	 * @param  array    $config
	 * @return void
	 */
	public function connect($config = array());

	/**
	 * Upload the file.
	 *
	 * @access public
	 * @param  string   $name   Extension name
	 * @return bool
	 */
	public function upload($name);

	/**
	 * Verify that the driver is connected to a service.
	 *
	 * @access public
	 * @return bool
	 */
	public  function connected();
}
