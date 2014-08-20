<?php namespace Orchestra\Foundation\Installation;

interface InstallerInterface
{
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
