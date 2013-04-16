<?php namespace Orchestra\Foundation\Tests\Model;

class RoleTest extends \PHPUnit_Framework_TestCase {

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
	 * Test Orchestra\Model\Role::users() method.
	 *
	 * @test
	 */
	public function testUsersMethod()
	{
		$model = new \Orchestra\Model\Role;

		$this->addMockConnection($model);
		
		$stub = $model->users();

		$this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsToMany', $stub);
		$this->assertInstanceOf('\Orchestra\Model\User', $stub->getQuery()->getModel());
	}
}