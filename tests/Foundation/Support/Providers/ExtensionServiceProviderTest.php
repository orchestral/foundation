<?php namespace Orchestra\Foundation\Support\Providers\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Foundation\Support\Providers\ExtensionServiceProvider;

class ExtensionServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Providers\ExtensionServiceProvider
     * is deferred.
     *
     * @test
     */
    public function testServiceProviderIsDeferred()
    {
        $stub = new ExtensionServiceProvider(null);

        $this->assertTrue($stub->isDeferred());
    }

    /**
     * Test Orchestra\Foundation\Providers\ExtensionServiceProvider::register()
     * method.
     *
     * @test
     */
    public function testRegisterMethod()
    {
        $stub = new ExtensionServiceProvider(null);

        $this->assertNull($stub->register());
    }

    /**
     * Test Orchestra\Foundation\Providers\ExtensionServiceProvider::boot()
     * method.
     *
     * @test
     */
    public function testBootMethod()
    {
        $app                               = new Container();
        $app['orchestra.extension.finder'] = $finder = m::mock('\Orchestra\Extension\Finder');

        $finder->shouldReceive('addPath')->once()->with('app::Extensions/*/*')->andReturnNull()
            ->shouldReceive('registerExtension')->once()->with('forum', 'base::modules/forum')->andReturnNull();

        $stub = new StubExtensionProvider($app);

        $this->assertNull($stub->boot());
    }

    /**
     * Test Orchestra\Foundation\Providers\ExtensionServiceProvider::when()
     * method.
     *
     * @test
     */
    public function testWhenIsProvided()
    {
        $stub = new ExtensionServiceProvider(null);

        $this->assertContains('orchestra.extension: detecting', $stub->when());
    }
}

class StubExtensionProvider extends ExtensionServiceProvider
{
    protected $extensions = [
        'app::Extensions/*/*',
        'forum' => 'base::modules/forum',
    ];
}
