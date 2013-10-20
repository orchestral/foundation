<?php namespace Orchestra\Foundation\Installation;

interface RequirementInterface
{
    /**
     * Check all requirement.
     *
     * @return boolean
     */
    public function check();

    /**
     * Get checklist result.
     *
     * @return array
     */
    public function getChecklist();

    /**
     * Get installable status.
     *
     * @return boolean
     */
    public function isInstallable();
}
