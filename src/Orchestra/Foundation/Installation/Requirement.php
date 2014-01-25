<?php namespace Orchestra\Foundation\Installation;

use PDOException;

class Requirement implements RequirementInterface
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Installation checklist for Orchestra Platform.
     *
     * @var array
     */
    protected $checklist = array();

    /**
     * Installable status.
     *
     * @var boolean
     */
    protected $installable = true;

    /**
     * Construct a new instance.
     *
     * @param  \Illuminate\Foundation\Application   $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Check all requirement.
     *
     * @return boolean
     */
    public function check()
    {
        $this->checklist['databaseConnection'] = $this->checkDatabaseConnection();
        $this->checklist['writableStorage']    = $this->checkWritableStorage();
        $this->checklist['writableAsset']      = $this->checkWritableAsset();

        foreach ($this->checklist as $requirement) {
            if ($requirement['is'] !== $requirement['should'] && true === $requirement['explicit']) {
                $this->installable = false;
            }
        }

        return $this->installable;
    }

    /**
     * Check database connection.
     *
     * @return array
     */
    public function checkDatabaseConnection()
    {
        $schema = array('is' => true);

        try {
            $this->app['db']->connection()->getPdo();
        } catch (PDOException $e) {
            $schema['is'] = false;
            $schema['data']['error'] = $e->getMessage();
        }

        return array_merge($this->getChecklistSchema(), $schema);
    }

    /**
     * Check whether storage folder is writable.
     *
     * @return array
     */
    public function checkWritableStorage()
    {
        $path   = rtrim($this->app['path.storage'], '/').'/';
        $schema = array(
            'is'   => $this->checkPathIsWritable($path),
            'data' => array(
                'path' => $this->app['html']->create('code', 'storage', array('title' => $path)),
            ),
        );

        return array_merge($this->getChecklistSchema(), $schema);
    }

    /**
     * Check whether asset folder is writable.
     *
     * @return array
     */
    public function checkWritableAsset()
    {
        $path   = rtrim($this->app['path.public'], '/').'/packages/';
        $schema = array(
            'is'   => $this->checkPathIsWritable($path),
            'data' => array(
                'path' => $this->app['html']->create('code', 'public/packages', array('title' => $path)),
            ),
            'explicit' => false,
        );

        return array_merge($this->getChecklistSchema(), $schema);
    }

    /**
     * Get checklist schema.
     *
     * @return array
     */
    protected function getCheckListSchema()
    {
        return array(
            'is'       => null,
            'should'   => true,
            'explicit' => true,
            'data'     => array(),
        );
    }

    /**
     * Check if path is writable.
     *
     * @param  string   $path
     * @return boolean
     */
    protected function checkPathIsWritable($path)
    {
        return $this->app['files']->isWritable($path);
    }

    /**
     * Get checklist result.
     *
     * @return array
     */
    public function getChecklist()
    {
        return $this->checklist;
    }

    /**
     * Get installable status.
     *
     * @return boolean
     */
    public function isInstallable()
    {
        return $this->installable;
    }
}
