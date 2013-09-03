<?php namespace Orchestra\Foundation\Model\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Facade;
use Illuminate\Foundation\Application;
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
	 * Setup the test environment.
	 */
	public function setUp()
	{
		Facade::clearResolvedInstances();
		Facade::setFacadeApplication(new Application);
	}

	/**
	 * Teardown the test environment.
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
		$model = new Role;

		$this->addMockConnection($model);
		
		$stub = $model->users();

		$this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsToMany', $stub);
		$this->assertInstanceOf('\Orchestra\Model\User', $stub->getQuery()->getModel());
	}

	/**
	 * Test Orchestra\Model\Role::admin() method.
	 *
	 * @test
	 */
	public function testAdminMethod()
	{
		Config::swap($config = m::mock('Config\Manager'));

		$config->shouldReceive('get')->once()->with('orchestra/foundation::roles.admin')->andReturn(1);

		$model = new Role;
		
		$resolver = m::mock('Illuminate\Database\ConnectionResolverInterface');
		$model->setConnectionResolver($resolver);
		$resolver->shouldReceive('connection')
			->andReturn($connection = m::mock('Illuminate\Database\Connection'));
		$model->getConnection()
			->shouldReceive('getQueryGrammar')
				->andReturn($grammar = m::mock('Illuminate\Database\Query\Grammars\Grammar'));
		$model->getConnection()
			->shouldReceive('getPostProcessor')
				->andReturn($processor = m::mock('Illuminate\Database\Query\Processors\Processor'));

		$grammar->shouldReceive('compileSelect')->once()->andReturn('SELECT * FROM `roles` WHERE id=?');
		$connection->shouldReceive('select')->once()->with('SELECT * FROM `roles` WHERE id=?', array(1))->andReturn(null);
		$processor->shouldReceive('processSelect')->once()->andReturn(array());
		
		$model->admin();
	}

	/**
	 * Test Orchestra\Model\Role::member() method.
	 *
	 * @test
	 */
	public function testMemberMethod()
	{
		Config::swap($config = m::mock('Config\Manager'));

		$config->shouldReceive('get')->once()->with('orchestra/foundation::roles.member')->andReturn(2);

		$model = new Role;
		
		$resolver = m::mock('Illuminate\Database\ConnectionResolverInterface');
		$model->setConnectionResolver($resolver);
		$resolver->shouldReceive('connection')
			->andReturn($connection = m::mock('Illuminate\Database\Connection'));
		$model->getConnection()
			->shouldReceive('getQueryGrammar')
				->andReturn($grammar = m::mock('Illuminate\Database\Query\Grammars\Grammar'));
		$model->getConnection()
			->shouldReceive('getPostProcessor')
				->andReturn($processor = m::mock('Illuminate\Database\Query\Processors\Processor'));

		$grammar->shouldReceive('compileSelect')->once()->andReturn('SELECT * FROM `roles` WHERE id=?');
		$connection->shouldReceive('select')->once()->with('SELECT * FROM `roles` WHERE id=?', array(2))->andReturn(null);
		$processor->shouldReceive('processSelect')->once()->andReturn(array());
		
		$model->member();
	}
}
