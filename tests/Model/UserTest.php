<?php namespace Orchestra\Foundation\Tests\Model;

class UserTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test Orchestra\Foundation\Model\User::getAuthIdentifier() method.
	 *
	 * @test
	 * @group model
	 */
	public function testGetAuthIdentifierMethod()
	{
		$stub = new \Orchestra\Foundation\Model\User;
		$stub->id = 5;

		$this->assertEquals(5, $stub->getAuthIdentifier());
	}

	/**
	 * Test Orchestra\Foundation\Model\User::getAuthPassword() method.
	 *
	 * @test
	 * @group model
	 */
	public function testGetAuthPasswordMethod()
	{
		$stub = new \Orchestra\Foundation\Model\User;
		$stub->password = 'foo';

		$this->assertEquals('foo', $stub->getAuthPassword());
	}

	/**
	 * Test Orchestra\Foundation\Model\User::getReminderEmail() method.
	 * 
	 * @test
	 * @group model
	 */
	public function testGetReminderEmailMethod()
	{
		$stub = new \Orchestra\Foundation\Model\User;
		$stub->email = 'admin@orchestraplatform.com';

		$this->assertEquals('admin@orchestraplatform.com', $stub->getReminderEmail());
	}
}