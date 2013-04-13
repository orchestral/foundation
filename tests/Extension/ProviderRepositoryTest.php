<?php namespace Orchestra\Foundation\Test\Extension;

class ProviderRepositoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test Orchestra\Foundation\Extension\ProviderRepository::services() 
	 * method.
	 *
	 * @test
	 */
	public function testServicesMethod()
	{
		$request = \Mockery::mock('\Illuminate\Http\Request');
		$request->shouldReceive('ajax')->andReturn(null);

		$provider = \Mockery::mock('\Illuminate\Foundation\ProviderRepository');

		$app = new \Illuminate\Foundation\Application($request);
		$app['orchestra.service.provider'] = $provider;
		
		$provider->shouldReceive('load')
			->once()->with($app, array('Orchestra\Foo\FooServiceProvider'))
				->andReturn(null);

		$stub = new \Orchestra\Foundation\Extension\ProviderRepository($app);
		$stub->services(array('Orchestra\Foo\FooServiceProvider'));
	}
}