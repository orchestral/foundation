<?php namespace Orchestra\Foundation\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

abstract class ApplicationTestCase extends TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		parent::setUp();

		$this->environmentSetUp();
		$this->installationSetUp();
	}
	
	/**
	 * Setup database connection.
	 *
	 * @return void
	 */
	protected function environmentSetUp() 
	{
		Config::set('auth.model', 'Orchestra\Model\User');
	}

	/**
	 * Installation Setup.
	 *
	 * @return void
	 */
	protected function installationSetUp()
	{
		$installer   = App::make('Orchestra\Foundation\Installation\InstallerInterface');
		$requirement = App::make('Orchestra\Foundation\Installation\RequirementInterface');

		if ( ! $requirement->check())
		{
			$this->markTestIncomplete('This testcase requirement a database connection.');
		} 

		$installer->migrate();
		
		if ( !$this->installer->createAdmin($this->getApplicationFixture()))
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
