<?php namespace Orchestra\Foundation\TestCase;

use Mockery as m;
use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Orchestra\Foundation\Testing\TestCase;

class ServiceProviderTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
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
        $this->assertInstanceOf('\Orchestra\Support\Ftp', $stub);
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
     * Test instance of auth reminder.
     *
     * @test
     */
    public function testInstanceOfAuthReminder()
    {
        $app = App::getFacadeApplication();
        $app['auth.reminder.repository'] = m::mock('\Illuminate\Auth\Reminders\DatabaseReminderRepository');
        $app['auth'] = $user = m::mock('\Illuminate\Auth\UserProviderInterface');

        $user->shouldReceive('driver')->once()->andReturn($user)
            ->shouldReceive('getProvider')->once()->andReturn($user);

        $stub = App::make('auth.reminder');
        $this->assertInstanceOf('\Orchestra\Foundation\Reminders\PasswordBroker', $stub);
    }

    /**
     * Test auth event listeners.
     *
     * @test
     */
    public function testAuthListeners()
    {
        $app = App::getFacadeApplication();

        $this->assertEquals(array('Guest'), Auth::roles());

        $user = m::mock('\Orchestra\Model\User[getRoles]');
        $user->id = 1;

        $user->shouldReceive('getRoles')->once()->andReturn(array(
            'Administrator',
        ));

        $this->assertEquals(
            array('Administrator'),
            $app['events']->until('orchestra.auth: roles', array($user, array()))
        );
    }

    /**
     * Test list of provides.
     *
     * @test
     */
    public function testListOfProvides()
    {
        $app = App::getFacadeApplication();

        $foundation = new \Orchestra\Foundation\FoundationServiceProvider($app);
        $site       = new \Orchestra\Foundation\SiteServiceProvider($app);
        $reminder   = new \Orchestra\Foundation\Reminders\ReminderServiceProvider($app);
        $console    = new \Orchestra\Foundation\ConsoleSupportServiceProvider($app);

        $foundationProvides = array(
            'orchestra.app',
            'orchestra.installed',
        );
        $siteProvides = array(
            'orchestra.publisher',
            'orchestra.publisher.ftp',
            'orchestra.site',
            'orchestra.role',
            'orchestra.user',
        );
        $reminderProvides = array(
            'auth.reminder',
            'auth.reminder.repository',
            'command.auth.reminders',
        );
        $consoleProvides = array(
            'orchestra.commands.auth',
            'command.debug',
            'orchestra.commands.extension.activate',
            'orchestra.commands.extension.deactivate',
            'orchestra.commands.extension.detect',
            'orchestra.commands.extension.migrate',
            'orchestra.commands.extension.publish',
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
        $app = m::mock('\Illuminate\Foundation\Application[after]');
        $app['events'] = $events = m::mock('\Illuminate\Events\Dispatcher[fire]');

        $events->shouldReceive('fire')->once()->with('orchestra.done')->andReturnNull();

        $app->shouldReceive('after')->once()->with(m::type('Closure'))
            ->andReturnUsing(function ($c) {
                $c();
            });

        $foundation = new \Orchestra\Foundation\FoundationServiceProvider($app);
        $foundation->register();
    }
}
