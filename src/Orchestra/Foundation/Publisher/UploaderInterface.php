<?php namespace Orchestra\Foundation\Publisher;

interface UploaderInterface {

	/**
	 * Get service connection instance.
	 *
	 * @access public
	 * @return self
	 */
	public function getConnection();

	/**
	 * Get service connection instance.
	 *
	 * @access public
	 * @param  Object   $client
	 * @return void
	 */
	public function setConnection($client);

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
	 * @return boolean
	 */
	public function upload($name);

	/**
	 * Verify that the driver is connected to a service.
	 *
	 * @access public
	 * @return boolean
	 */
	public function connected();
}
