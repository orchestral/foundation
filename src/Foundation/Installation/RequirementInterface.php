<?php namespace Orchestra\Foundation\Installation;

interface RequirementInterface
{
    /**
     * Check all requirement.
     *
     * @return bool
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
     * @return bool
     */
    public function isInstallable();
}
