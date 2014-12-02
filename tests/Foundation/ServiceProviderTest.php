<?php namespace Orchestra\Foundation\TestCase;

use Mockery as m;
use Illuminate\Foundation\Application;
use Orchestra\Foundation\Testing\TestCase;
use Orchestra\Auth\Passwords\PasswordResetServiceProvider;
use Orchestra\Foundation\Providers\SupportServiceProvider;
use Orchestra\Foundation\Providers\FoundationServiceProvider;
use Orchestra\Foundation\Providers\ConsoleSupportServiceProvider;

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
        $stub = $this->app->make('orchestra.publisher');
        $this->assertInstanceOf('\Orchestra\Foundation\Publisher\PublisherManager', $stub);

        $stub = $this->app->make('orchestra.publisher.ftp');
        $this->assertInstanceOf('\Orchestra\Support\Ftp\Client', $stub);
    }

    /**
     * Test instance of eloquents.
     *
     * @test
     */
    public function testInstanceOfEloquents()
    {
        $stub = $this->app->make('orchestra.role');
        $this->assertInstanceOf('\Orchestra\Model\Role', $stub);

        $stub = $this->app->make('orchestra.user');
        $this->assertInstanceOf('\Orchestra\Model\User', $stub);
    }

    /**
     * Test instance of auth password broker.
     *
     * @test
     */
    public function testInstanceOfAuthPasswordBroker()
    {
        $app = $this->app;
        $app['auth.password.tokens'] = m::mock('\Illuminate\Auth\Passwords\TokenRepositoryInterface');
        $app['auth'] = $user = m::mock('\Illuminate\Auth\UserProviderInterface');

        $user->shouldReceive('driver')->once()->andReturn($user)
            ->shouldReceive('getProvider')->once()->andReturn($user);

        $stub = $this->app->make('auth.password');
        $this->assertInstanceOf('\Orchestra\Auth\Passwords\PasswordBroker', $stub);
    }

    /**
     * Test list of provides.
     *
     * @test
     */
    public function testListOfProvides()
    {
        $foundation = new FoundationServiceProvider($this->app);
        $site       = new SupportServiceProvider($this->app);
        $reminder   = new PasswordResetServiceProvider($this->app);
        $console    = new ConsoleSupportServiceProvider($this->app);

        $foundationProvides = array(
            'orchestra.app',
            'orchestra.installed',
            'orchestra.meta',
        );
        $siteProvides = array(
            'orchestra.publisher',
            'orchestra.publisher.ftp',
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
            'orchestra.view.command.detect',
            'orchestra.view.command.activate',
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
}
