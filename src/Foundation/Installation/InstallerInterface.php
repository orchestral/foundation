<?php namespace Orchestra\Foundation\Installation;

interface InstallerInterface
{
    /**
     * Boot installer files.
     *
     * @return void
     */
    public function bootInstallerFiles();

    /**
     * Migrate Orchestra Platform schema.
     *
     * @return bool
     */
    public function migrate();

    /**
     * Create administrator account.
     *
     * @param  array    $input
     * @param  bool     $multipleAdmin
     * @return bool
     */
    public function createAdmin($input, $multipleAdmin = true);
}
