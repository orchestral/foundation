<?php namespace Orchestra\Foundation\Tests\Model;

class UserTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Set mock connection
	 */
	protected function addMockConnection($model)
	{
		$model->setConnectionResolver(
			$resolver = \Mockery::mock('Illuminate\Database\ConnectionResolverInterface')
		);
		$resolver->shouldReceive('connection')
			->andReturn(\Mockery::mock('Illuminate\Database\Connection'));
		$model->getConnection()
			->shouldReceive('getQueryGrammar')
				->andReturn(\Mockery::mock('Illuminate\Database\Query\Grammars\Grammar'));
		$model->getConnection()
			->shouldReceive('getPostProcessor')
				->andReturn(\Mockery::mock('Illuminate\Database\Query\Processors\Processor'));
	}

	/**
	 * Teardown test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test Orchestra\Foundation\Model\User::roles() method.
	 *
	 * @test
	 */
	public function testRolesMethod()
	{
		$model = new \Orchestra\Foundation\Model\User;

		$this->addMockConnection($model);
		
		$stub = $model->roles();

		$this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsToMany', $stub);
		$this->assertInstanceOf('\Orchestra\Foundation\Model\Role', $stub->getQuery()->getModel());
	}

	/**
	 * Test Orchestra\Foundation\Model\User::getAuthIdentifier() method.
	 *
	 * @test
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
	 */
	public function testGetReminderEmailMethod()
	{
		$stub = new \Orchestra\Foundation\Model\User;
		$stub->email = 'admin@orchestraplatform.com';

		$this->assertEquals('admin@orchestraplatform.com', $stub->getReminderEmail());
	}
}