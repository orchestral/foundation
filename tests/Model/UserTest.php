<?php namespace Orchestra\Foundation\Tests\Model;

use Mockery as m;
use Orchestra\Model\User;

class UserTest extends \PHPUnit_Framework_TestCase {

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
	 * Test Orchestra\Model\User::roles() method.
	 *
	 * @test
	 */
	public function testRolesMethod()
	{
		$model = new User;

		$this->addMockConnection($model);
		
		$stub = $model->roles();

		$this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsToMany', $stub);
		$this->assertInstanceOf('\Orchestra\Model\Role', $stub->getQuery()->getModel());
	}

	/**
	 * Test Orchestra\Model\User::scopeSearch() method.
	 *
	 * @test
	 */
	public function testScopeSearchMethod()
	{
		$model = new User;
		$this->addMockConnection($model);

		$keyword = 'foo';
		$roles   = array('admin');

		$query = m::mock('QueryBuilder');
		$query->shouldReceive('with')->once()->with('roles')->andReturn($query)
			->shouldReceive('whereNotNull')->once()->with('users.id')->andReturn($query)
			->shouldReceive('join')->once()->with('user_role', 'users.id', '=', 'user_role.user_id')->andReturn($query)
			->shouldReceive('whereIn')->once()->with('user_role.role_id', $roles)->andReturn(null)
			->shouldReceive('where')->once()->with('email', 'LIKE', $keyword)->andReturn($query)
			->shouldReceive('orWhere')->once()->with('fullname', 'LIKE', $keyword)->andReturn(null)
			->shouldReceive('where')->once()->with(m::type('Closure'))->andReturnUsing(function ($q) use ($query, $keyword)
			{
				$q($query);
			});

		$this->assertEquals($query, $model->scopeSearch($query, $keyword, $roles));

	}

	/**
	 * Test Orchestra\Model\User::getAuthIdentifier() method.
	 *
	 * @test
	 */
	public function testGetAuthIdentifierMethod()
	{
		$stub = new User;
		$stub->id = 5;

		$this->assertEquals(5, $stub->getAuthIdentifier());
	}

	/**
	 * Test Orchestra\Model\User::getAuthPassword() method.
	 *
	 * @test
	 */
	public function testGetAuthPasswordMethod()
	{
		\Illuminate\Support\Facades\Hash::shouldReceive('make')
			->once()->with('foo')->andReturn('foobar');

		$stub = new User;
		$stub->password = 'foo';

		$this->assertEquals('foobar', $stub->getAuthPassword());
	}

	/**
	 * Test Orchestra\Model\User::getReminderEmail() method.
	 * 
	 * @test
	 */
	public function testGetReminderEmailMethod()
	{
		$stub        = new User;
		$stub->email = 'admin@orchestraplatform.com';

		$this->assertEquals('admin@orchestraplatform.com', $stub->getReminderEmail());
	}
}
