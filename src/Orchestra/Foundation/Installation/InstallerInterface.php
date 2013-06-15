<?php namespace Orchestra\Foundation\Installation;

interface InstallerInterface {

	/**
	 * Migrate Orchestra Platform schema.
	 *
	 * @access public
	 * @return boolean
	 */
	public function migrate();
	
	/**
	 * Create adminstrator account.
	 *
	 * @access public	
	 * @param  array    $input
	 * @return boolean
	 */
	public function createAdmin($input, $multipleAdmin = true);
}
