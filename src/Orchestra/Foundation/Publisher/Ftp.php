<?php namespace Orchestra\Foundation\Publisher;

use RuntimeException;
use Orchestra\Support\Ftp as FtpClient;
use Orchestra\Support\Ftp\ServerException;

class Ftp implements UploaderInterface {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * FTP Connection instance.
	 * 
	 * @var Orchestra\Support\FTP
	 */
	protected $connection = null;

	/**
	 * Construct a new FTP instance.
	 *
	 * @access public
	 * @param  Illuminate\Foundation\Application    $app
	 * @param  Orchestra\Support\Ftp                $client
	 * @return void
	 */
	public function __construct($app, FtpClient $client)
	{
		$this->app = $app;
		$this->setConnection($client);

		// If FTP credential is stored in the session, we should reuse it 
		// and connect to FTP server straight away.
		$config = $this->app['session']->get('orchestra.ftp', array());

		try 
		{
			$this->connect($config);
		}
		catch (ServerException $e)
		{
			// Connection might failed, but there nothing really to report.
			$this->app['session']->put('orchestra.ftp', array());
		}
	}

	/**
	 * Get service connection instance.
	 *
	 * @access public
	 * @return Orchestra\Support\FTP
	 */
	public function getConnection()
	{
		return $this->connection;
	}

	/**
	 * Set service connection instance.
	 *
	 * @access public
	 * @param  Orchestra\Support\FTP    $client
	 * @return void
	 */
	public function setConnection(FtpClient $client)
	{
		$this->connection = $client;
	}

	/**
	 * Connect to the service.
	 *
	 * @access public	
	 * @param  array    $config
	 * @return bool
	 */
	public function connect($config = array())
	{
		$this->connection->setUp($config);
		return $this->connection->connect();
	}

	/**
	 * CHMOD a directory/file.
	 *
	 * @access private
	 * @param  string   $path
	 * @param  int      $mode
	 * @return bool
	 */
	private function chmod($path, $mode = 0755)
	{
		return $this->connection->chmod($path, $mode);
	}

	/**
	 * Check chmod for a file/directory recursively.
	 *
	 * @access private
	 * @param  string   $path
	 * @param  int      $mode
	 * @return bool
	 */
	private function recursiveChmod($path, $mode = 0755)
	{
		$this->chmod($path, $mode);

		try
		{
			$lists = $this->connection->ls($path);

			// this is to check if return value is just a single file, 
			// avoiding infinite loop when we reach a file.
			if ($lists === array($path)) return true;

			foreach ($lists as $dir)
			{
				// Not a file or folder, ignore it.
				if (substr($dir, -3) === '/..' or substr($dir, -2) === '/.') continue;
				
				$this->recursiveChmod($dir, $mode);
			}
		}
		catch (RuntimeException $e)
		{
			// Do nothing.
		}

		return true;
	}


	/**
	 * Upload the file.
	 *
	 * @access public
	 * @param  string   $name           Extension name
	 * @param  bool     $recursively
	 * @return bool
	 */
	public function upload($name, $recursively = false)
	{
		$public = $this->basePath($this->app['path.public']);

		// Start chmod from public/packages directory, if the extension folder
		// is yet to be created, it would be created and own by the web server
		// (Apache or Nginx). If otherwise, we would then emulate chmod -Rf
		$public = rtrim($public, DS).DS;
		$path   = $public.'packages'.DS;

		// If the extension directory exist, we should start chmod from the
		// folder instead.
		if (is_dir(path('public').'packages'.DS.$name.DS)) 
		{
			$recursively = true;
			$path        = $path.$name.DS;
		}

		try 
		{
			($recursively ? $this->recursiveChmod($path, 0777) : $this->chmod($path, 0777));
		}
		catch (RuntimeException $e)
		{
			// We found an exception with FTP, but it would be hard to say 
			// extension can't be activated, let's try activating the 
			// extension and if it failed, we should actually catching 
			// those exception instead.
		}

		$this->app['orchestra.extension']->activate($name);
		
		// Revert chmod back to original state.
		($recursively ? $this->recursiveChmod($path, 0755) : $this->chmod($path, 0755));
		
		return true;
	}

	/**
	 * Get base path for FTP
	 *
	 * @access public
	 * @param  string   $path
	 * @return string
	 */
	public function basePath($path)
	{
		// This set of preg_match would filter ftp' user is not accessing 
		// exact path as path('public'), in most shared hosting ftp' user 
		// would only gain access to it's /home/username directory.
		if (preg_match('/^\/(home)\/([a-zA-Z0-9]+)\/(.*)$/', $path, $matches))
		{
			$path = DS.ltrim($matches[3], DS);
		}

		return $path;
	}

	/**
	 * Verify that FTP driver is connected to a service.
	 * 
	 * @access public
	 * @return bool
	 */
	public function connected()
	{
		if (is_null($this->connection)) return false;

		return $this->connection->connected();
	}
}
