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
		$model = new \Orchestra\Model\User;

		$this->addMockConnection($model);
		
		$stub = $model->roles();

		$this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsToMany', $stub);
		$this->assertInstanceOf('\Orchestra\Model\Role', $stub->getQuery()->getModel());
	}

	/**
	 * Test Orchestra\Model\User::getAuthIdentifier() method.
	 *
	 * @test
	 */
	public function testGetAuthIdentifierMethod()
	{
		$stub = new \Orchestra\Model\User;
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
		$app = \Mockery::mock('Application');
		$app->shouldReceive('instance')->andReturn(true);

		\Illuminate\Support\Facades\Hash::setFacadeApplication($app);
		\Illuminate\Support\Facades\Hash::swap($hash = \Mockery::mock('Hash'));
		$hash->shouldReceive('make')->once()->with('foo')->andReturn('foobar');

		$stub = new \Orchestra\Model\User;
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
		$stub = new \Orchestra\Model\User;
		$stub->email = 'admin@orchestraplatform.com';

		$this->assertEquals('admin@orchestraplatform.com', $stub->getReminderEmail());
	}
}
