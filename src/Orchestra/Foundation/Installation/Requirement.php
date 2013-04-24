<?php namespace Orchestra\Foundation\Installation;

use PDOException,
	Illuminate\Support\Facades\DB,
	Illuminate\Support\Facades\Html;

class Requirement {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;
	
	/**
	 * Installation checklist for Orchestra Platform.
	 *
	 * @var array
	 */
	protected $checklist = array();

	/**
	 * Installable status
	 *
	 * @var boolean
	 */
	protected $installable = true;

	/**
	 * Construct a new instance.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Check all requirement.
	 *
	 * @access public
	 * @return 
	 */
	public function check()
	{
		$this->checklist['databaseConnection'] = $this->checkDatabaseConnection();
		$this->checklist['writableStorage']    = $this->checkWritableStorage();
		$this->checklist['writableAsset']      = $this->checkWritableAsset();

		foreach ($this->checklist as $requirement)
		{
			if ($requirement['is'] !== $requirement['should'] 
				and true === $requirement['explicit'])
			{
				$this->installable = false;
			}
		}

		return $this->installable;
	}

	/**
	 * Check database connection.
	 *
	 * @access public
	 * @return array
	 */
	public function checkDatabaseConnection()
	{
		$schema = array(
			'is' => true,
		);

		try
		{
			DB::connection()->getPdo();
		}
		catch (PDOException $e)
		{
			$schema['is'] = false;
		}

		return array_merge($this->getChecklistSchema(), $schema);
	}

	/**
	 * Check whether storage folder is writable.
	 *
	 * @access public
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
	 * @access public
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
	 * @access protected
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
	 * @access protected
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
	 * @access public
	 * @return array
	 */
	public function getChecklist()
	{
		return $this->checklist;
	}

	/**
	 * Get installable status.
	 * 
	 * @access public
	 * @return bool
	 */
	public function isInstallable()
	{
		return $this->installable;
	}
}