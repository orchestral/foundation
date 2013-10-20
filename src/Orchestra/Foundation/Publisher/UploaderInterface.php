<?php namespace Orchestra\Foundation\Publisher;

interface UploaderInterface
{
    /**
     * Get service connection instance.
     *
     * @return self
     */
    public function getConnection();

    /**
     * Get service connection instance.
     *
     * @param  Object   $client
     * @return void
     */
    public function setConnection($client);

    /**
     * Connect to the service.
     *
     * @param  array    $config
     * @return void
     */
    public function connect($config = array());

    /**
     * Upload the file.
     *
     * @param  string   $name   Extension name
     * @return boolean
     */
    public function upload($name);

    /**
     * Verify that the driver is connected to a service.
     *
     * @return boolean
     */
    public function connected();
}
