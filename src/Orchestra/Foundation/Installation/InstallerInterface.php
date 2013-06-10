<?php namespace Orchestra\Foundation\Installation;

interface InstallerInterface {

	/**
	 * Migrate Orchestra Platform schema.
	 *
	 * @access public
	 * @return void
	 */
	public function migrate();
	
	/**
	 * Create adminstrator account.
	 *
	 * @access public	
	 * @param  array    $input
	 * @return void
	 */
	public function createAdmin($input, $multipleAdmin = true);
}
