<?php namespace Orchestra\Foundation\Installation;

interface RequirementInterface {

	/**
	 * Check all requirement.
	 *
	 * @access public
	 * @return boolean
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
	 * @return boolean
	 */
	public function isInstallable();
	
}
