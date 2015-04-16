<?php namespace Orchestra\Foundation\TestCase;

use Mockery as m;
use Orchestra\Foundation\Providers\ArtisanServiceProvider;
use Orchestra\Testing\TestCase;
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
     * Test list of provides.
     *
     * @test
     */
    public function testListOfProvides()
    {
        $foundation = new FoundationServiceProvider($this->app);
        $site       = new SupportServiceProvider($this->app);
        $console    = new ConsoleSupportServiceProvider($this->app);
        $artisan    = new ArtisanServiceProvider($this->app);

        $this->assertEquals($this->getFoundationProvides(), $foundation->provides());
        $this->assertFalse($foundation->isDeferred());

        $this->assertEquals($this->getSupportProvides(), $site->provides());
        $this->assertTrue($site->isDeferred());

        $this->assertEquals($this->getConsoleSupportProvides(), $console->provides());
        $this->assertTrue($console->isDeferred());

        $this->assertInstanceOf('\Orchestra\Config\Console\ConfigCacheCommand', $this->app['command.config.cache']);
        $this->assertTrue($artisan->isDeferred());
    }

    /**
     * Get value of Orchestra\Foundation\Providers\FoundationServiceProvider::provides().
     *
     * @return array
     */
    protected function getFoundationProvides()
    {
        return [
            'orchestra.app',
            'orchestra.installed',
            'orchestra.meta',
        ];
    }

    /**
     * Get value of Orchestra\Foundation\Providers\SupportServiceProvider::provides().
     *
     * @return array.
     */
    protected function getSupportProvides()
    {
        return [
            'orchestra.publisher',
            'orchestra.role',
            'orchestra.user',
        ];
    }

    /**
     * Get value of Orchestra\Foundation\Providers\ConsoleSupportServiceProvider::provides().
     *
     * @return array
     */
    protected function getConsoleSupportProvides()
    {
        return [
            'command.auth.resets.clear',
            'Illuminate\Console\Scheduling\ScheduleRunCommand',
            'migrator',
            'migration.repository',
            'command.migrate',
            'command.migrate.rollback',
            'command.migrate.reset',
            'command.migrate.refresh',
            'command.migrate.install',
            'command.migrate.status',
            'migration.creator',
            'command.migrate.make',
            'seeder',
            'command.seed',
            'composer',
            'command.queue.table',
            'command.queue.failed',
            'command.queue.retry',
            'command.queue.forget',
            'command.queue.flush',
            'command.queue.failed-table',
            'command.controller.make',
            'command.middleware.make',
            'command.session.database',
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
            'asset.publisher',
            'command.asset.publish',
            'view.publisher',
            'command.view.publish',
            'orchestra.view.command.activate',
            'orchestra.view.command.detect',
            'orchestra.view.command.optimize',
        ];
    }
}
