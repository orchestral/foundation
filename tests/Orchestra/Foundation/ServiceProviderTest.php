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

        $roles = m::mock('\Orchestra\Model\Role[lists]');
        $user = m::mock('\Illuminate\Auth\UserInterface');
        $user->id = 1;

        $user->shouldReceive('roles')->once()->andReturn($roles);
        $roles->shouldReceive('lists')->once()->andReturn(array(
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

        $foundationProvides = array(
            'orchestra.app', 'orchestra.installed',
        );
        $siteProvides = array(
            'orchestra.publisher',
            'orchestra.publisher.ftp', 'orchestra.site',
            'orchestra.role', 'orchestra.user',
        );
        $authProvides = array(
            'auth.reminder', 'auth.reminder.repository', 'command.auth.reminders',
        );

        $this->assertEquals($foundationProvides, $foundation->provides());
        $this->assertEquals($siteProvides, $site->provides());
        $this->assertEquals($authProvides, $reminder->provides());
    }
}
