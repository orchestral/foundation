<?php namespace Orchestra\Foundation\TestCase;

use Mockery as m;
use Orchestra\Testing\TestCase;
use Orchestra\Foundation\Providers\ArtisanServiceProvider;
use Orchestra\Foundation\Providers\SupportServiceProvider;
use Orchestra\Foundation\Providers\FoundationServiceProvider;
use Orchestra\Foundation\Providers\ConsoleSupportServiceProvider;

class ServiceProviderTest extends TestCase
{
    protected function tearDown()
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
        $memory = m::mock('\Orchestra\Contracts\Memory\Provider');
        $this->app->instance('orchestra.platform.memory', $memory);

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
        $site = new SupportServiceProvider($this->app);
        $console = new ConsoleSupportServiceProvider($this->app);
        $artisan = new ArtisanServiceProvider($this->app);

        $this->assertEquals($this->getFoundationProvides(), $foundation->provides());
        $this->assertFalse($foundation->isDeferred());

        $this->assertEquals($this->getSupportProvides(), $site->provides());
        $this->assertTrue($site->isDeferred());

        $this->assertTrue($this->app->environment('testing'));
        $this->assertFalse($this->app->environment('production'));

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
            'command.clear-compiled',
            'command.auth.resets.clear',
            'command.config.cache',
            'command.config.clear',
            'command.down',
            'command.environment',
            'command.key.generate',
            'command.optimize',
            'command.route.cache',
            'command.route.clear',
            'command.route.list',
            'command.storage.link',
            'command.tinker',
            'command.up',
            'command.view.clear',
            'command.app.name',
            'command.auth.make',
            'command.cache.table',
            'command.console.make',
            'command.controller.make',
            'command.event.generate',
            'command.event.make',
            'command.job.make',
            'command.listener.make',
            'command.middleware.make',
            'command.model.make',
            'command.notification.make',
            'command.policy.make',
            'command.provider.make',
            'command.queue.failed-table',
            'command.queue.table',
            'command.request.make',
            'command.seeder.make',
            'command.session.table',
            'command.serve',
            'command.test.make',
            'command.vendor.publish',
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
            'command.queue.failed',
            'command.queue.retry',
            'command.queue.forget',
            'command.queue.flush',
            'orchestra.commands.auth',
            'orchestra.commands.extension.activate',
            'orchestra.commands.extension.deactivate',
            'orchestra.commands.extension.detect',
            'orchestra.commands.extension.migrate',
            'orchestra.commands.extension.publish',
            'orchestra.commands.extension.refresh',
            'orchestra.commands.extension.reset',
            'orchestra.commands.memory',
            'orchestra.commands.assemble',
            'orchestra.commands.optimize',
            'orchestra.optimize',
            'command.asset.publish',
            'command.config.publish',
            'command.view.publish',
            'asset.publisher',
            'config.publisher',
            'view.publisher',
            'orchestra.view.command.activate',
            'orchestra.view.command.detect',
            'orchestra.view.command.optimize',
        ];
    }
}
