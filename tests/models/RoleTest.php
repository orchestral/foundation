<?php namespace Orchestra\Foundation\Tests\Model;

use Mockery as m;
use Orchestra\Model\Role;

class RoleTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Set mock connection
	 */
	protected function addMockConnection($model)
	{
		$resolver = m::mock('Illuminate\Database\ConnectionResolverInterface');
		$model->setConnectionResolver($resolver);
		$resolver->shouldReceive('connection')
			->andReturn(m::mock('Illuminate\Database\Connection'));
		$model->getConnection()
			->shouldReceive('getQueryGrammar')
				->andReturn(m::mock('Illuminate\Database\Query\Grammars\Grammar'));
		$model->getConnection()
			->shouldReceive('getPostProcessor')
				->andReturn(m::mock('Illuminate\Database\Query\Processors\Processor'));
	}

	/**
	 * Teardown test environment.
	 */
	public function tearDown()
	{
		m::close();
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
