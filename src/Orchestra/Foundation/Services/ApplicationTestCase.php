<?php namespace Orchestra\Foundation\Services;

abstract class ApplicationTestCase extends TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		parent::setUp();

		$this->runInstallation();
	}
	
	/**
	 * Define environment setup.
	 * 
	 * @param  Illuminate\Foundation\Application    $app
	 * @return void
	 */
	protected function getEnvironmentSetUp($app) 
	{
		$app['config']->set('auth.model', 'Orchestra\Model\User');
	}

	/**
	 * Installation Setup.
	 *
	 * @return void
	 */
	protected function runInstallation()
	{
		$installer   = new \Orchestra\Foundation\Installation\Installer($this->app);
		$requirement = new \Orchestra\Foundation\Installation\Requirement($this->app);

		if ( ! $requirement->check())
		{
			$this->markTestIncomplete('This testcase requirement a database connection.');
		} 

		if ( ! $installer->migrate())
		{
			$this->markTestIncomplete('Unable to install the application.');
		}
		
		if ( ! $installer->createAdmin($this->getApplicationFixture()))
		{
			$this->markTestIncomplete('Unable to setup the application.');
		}
	}

	/**
	 * Define Administrator fixture.
	 *
	 * @return array
	 */
	protected function getApplicationFixture()
	{
		return array(
			'email'     => 'hello@orchestraplatform.com',
			'password'  => 'awesome',
			'fullname'  => 'Administrator',
			'site_name' => 'Orchestra Platform',
		);
	}
}
