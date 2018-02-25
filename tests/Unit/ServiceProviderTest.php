<?php

namespace Orchestra\Tests\Unit;

use Orchestra\Testbench\TestCase;
use Orchestra\Foundation\Providers\ArtisanServiceProvider;
use Orchestra\Foundation\Providers\SupportServiceProvider;
use Orchestra\Foundation\Providers\FoundationServiceProvider;
use Orchestra\Foundation\Providers\ConsoleSupportServiceProvider;

class ServiceProviderTest extends TestCase
{
    /**
     * Test list of provides.
     *
     * @test
     */
    public function it_provides_list_of_provides()
    {
        $foundation = new FoundationServiceProvider($this->app);
        $site = new SupportServiceProvider($this->app);
        $console = new ConsoleSupportServiceProvider($this->app);
        $artisan = new ArtisanServiceProvider($this->app);

        $this->assertEquals($this->getFoundationProvides(), $foundation->provides());
        $this->assertFalse($foundation->isDeferred());

        $this->assertEquals($this->getSupportProvides(), $site->provides());
        $this->assertTrue($site->isDeferred());

        foreach ($this->getConsoleSupportProvides() as $provide) {
            $this->assertContains($provide, $console->provides());
        }

        $this->assertTrue($console->isDeferred());
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
     * @return array
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
            'orchestra.commands.extension.activate',
            'orchestra.commands.extension.deactivate',
            'orchestra.commands.extension.detect',
            'orchestra.commands.extension.migrate',
            'orchestra.commands.extension.publish',
            'orchestra.commands.extension.refresh',
            'orchestra.commands.extension.reset',
            'orchestra.commands.assemble',
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
