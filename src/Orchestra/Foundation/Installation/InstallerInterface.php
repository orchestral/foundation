<?php namespace Orchestra\Foundation\Installation;

interface InstallerInterface {

	/**
	 * Migrate Orchestra Platform schema.
	 *
	 * @return boolean
	 */
	public function migrate();
	
	/**
	 * Create adminstrator account.
	 *
	 * @param  array    $input
	 * @return boolean
	 */
	public function createAdmin($input, $multipleAdmin = true);
}
