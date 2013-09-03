<?php namespace Orchestra\Foundation\Model\TestCase;

use Mockery as m;
use Orchestra\Model\UserMeta;

class UserMetaTest extends \PHPUnit_Framework_TestCase {

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
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Model\UserMeta::users() method.
	 *
	 * @test
	 */
	public function testUsersMethod()
	{
		$model = new UserMeta;

		$this->addMockConnection($model);
		
		$stub = $model->users();

		$this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsTo', $stub);
		$this->assertInstanceOf('\Orchestra\Model\User', $stub->getQuery()->getModel());
	}

	/**
	 * Test Orchestra\Model\UserMeta::search() method.
	 *
	 * @test
	 */
	public function testScopeSearchMethod()
	{
		$query = m::mock('Query');

		$query->shouldReceive('where')->once()->with('user_id', '=', 1)->andReturn($query)
			->shouldReceive('where')->once()->with('name', '=', 'foo')->andReturn($query);

		with(new UserMeta)->scopeSearch($query, 'foo', 1);
	}
}
