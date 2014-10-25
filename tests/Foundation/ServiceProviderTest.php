<?php namespace Orchestra\Foundation\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Application;
use Orchestra\Foundation\Testing\TestCase;
use Orchestra\Foundation\SupportServiceProvider;
use Orchestra\Foundation\FoundationServiceProvider;
use Orchestra\Foundation\ConsoleSupportServiceProvider;
use Orchestra\Auth\Passwords\PasswordResetServiceProvider;

class ServiceProviderTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /**
     * Test instance of `orchestra.publisher`.
     *
     * @test
     */
    public function testInstanceOfOrchestraPublisher()
    {
        $stub = App::make('orchestra.publisher');
        $this->assertInstanceOf('\Orchestra\Foundation\Publisher\PublisherManager', $stub);

        $stub = App::make('orchestra.publisher.ftp');
        $this->assertInstanceOf('\Orchestra\Support\Ftp\Client', $stub);
    }

    /**
     * Test instance of `orchestra.memory`.
     *
     * @test
     */
    public function testInstanceOfOrchestraMemory()
    {
        $stub = App::make('orchestra.memory')->driver('user');

        $this->assertInstanceOf('\Orchestra\Model\Memory\UserMetaProvider', $stub);
        $this->assertInstanceOf('\Orchestra\Memory\Provider', $stub);
    }

    /**
     * Test instance of eloquents.
     *
     * @test
     */
    public function testInstanceOfEloquents()
    {
        $stub = App::make('orchestra.role');
        $this->assertInstanceOf('\Orchestra\Model\Role', $stub);

        $stub = App::make('orchestra.user');
        $this->assertInstanceOf('\Orchestra\Model\User', $stub);
    }

    /**
     * Test instance of auth password broker.
     *
     * @test
     */
    public function testInstanceOfAuthPasswordBroker()
    {
        $app = App::getFacadeApplication();
        $app['auth.password.tokens'] = m::mock('\Illuminate\Auth\Passwords\TokenRepositoryInterface');
        $app['auth'] = $user = m::mock('\Illuminate\Auth\UserProviderInterface');

        $user->shouldReceive('driver')->once()->andReturn($user)
            ->shouldReceive('getProvider')->once()->andReturn($user);

        $stub = App::make('auth.password');
        $this->assertInstanceOf('\Orchestra\Auth\Passwords\PasswordBroker', $stub);
    }

    /**
     * Test list of provides.
     *
     * @test
     */
    public function testListOfProvides()
    {
        $app = App::getFacadeApplication();

        $foundation = new FoundationServiceProvider($app);
        $site       = new SupportServiceProvider($app);
        $reminder   = new PasswordResetServiceProvider($app);
        $console    = new ConsoleSupportServiceProvider($app);

        $foundationProvides = array(
            'orchestra.app',
            'orchestra.installed',
        );
        $siteProvides = array(
            'orchestra.publisher',
            'orchestra.publisher.ftp',
            'orchestra.meta',
            'orchestra.role',
            'orchestra.user',
        );
        $reminderProvides = array(
            'auth.password',
            'auth.password.tokens',
        );
        $consoleProvides = array(
            'orchestra.commands.auth',
            'orchestra.commands.extension.activate',
            'orchestra.commands.extension.deactivate',
            'orchestra.commands.extension.detect',
            'orchestra.commands.extension.migrate',
            'orchestra.commands.extension.publish',
            'orchestra.commands.extension.refresh',
            'orchestra.commands.extension.reset',
            'orchestra.commands.memory',
            'orchestra.commands.optimize',
            'orchestra.optimize',
        );

        $this->assertEquals($foundationProvides, $foundation->provides());
        $this->assertFalse($foundation->isDeferred());

        $this->assertEquals($siteProvides, $site->provides());
        $this->assertTrue($site->isDeferred());

        $this->assertEquals($reminderProvides, $reminder->provides());
        $this->assertTrue($reminder->isDeferred());

        $this->assertEquals($consoleProvides, $console->provides());
        $this->assertTrue($console->isDeferred());
    }

    public function testRegisterEventsOnAfter()
    {
        $app = new Application(m::mock('\Illuminate\Http\Request'));
        $app['events'] = $events = m::mock('\Illuminate\Contracts\Events\Dispatcher[fire]');
        $app['router'] = $router = m::mock('\Illuminate\Routing\Router');
        $events->shouldReceive('fire')->once()->with('orchestra.done')->andReturnNull();

        $router->shouldReceive('after')->once()->with(m::type('Closure'))
            ->andReturnUsing(function ($c) {
                $c();
            });

        $foundation = new FoundationServiceProvider($app);
        $foundation->register();
    }
}
