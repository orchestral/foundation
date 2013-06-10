<?php namespace Orchestra\Foundation\Installation;

interface RequirementInterface {

	/**
	 * Check all requirement.
	 *
	 * @access public
	 * @return 
	 */
	public function check();

	/**
	 * Get checklist result.
	 *
	 * @access public
	 * @return array
	 */
	public function getChecklist();

	/**
	 * Get installable status.
	 * 
	 * @access public
	 * @return bool
	 */
	public function isInstallable();
	
}
