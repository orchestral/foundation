<?php

namespace Orchestra\Tests\Console;

use Mockery as m;
use Orchestra\Contracts\Foundation\Foundation;
use Orchestra\Contracts\Memory\Provider as MemoryProvider;

class ConfigureMailTest extends TestCase
{
    /** @test */
    public function it_cant_sync_configuration_when_orchestra_is_not_installed()
    {
        $this->app->instance('orchestra.app', $foundation = m::mock(Foundation::class));

        $foundation->shouldReceive('installed')->andReturn(false)
            ->shouldReceive('memory')->andReturn(m::mock(MemoryProvider::class));

        $this->artisan('orchestra:configure-email')
            ->assertExitCode(1);
    }

    /** @test */
    public function it_cant_sync_configuration_when_orchestra_is_installed()
    {
        $this->app->instance('orchestra.app', $foundation = m::mock(Foundation::class));

        $foundation->shouldReceive('installed')->andReturn(true)
            ->shouldReceive('memory')->andReturn($memory = m::mock(MemoryProvider::class));

        $memory->shouldReceive('get')->once()->with('email.from.name')->andReturn('Orchestra Platform')
            ->shouldReceive('get')->once()->with('email.from.address')->andReturn('hello@orchestraplatform.com')
            ->shouldReceive('put')->once()->with('email', ['driver' => 'smtp', 'host' => 'smtp.mailgun.org', 'port' => 587, 'encryption' => 'tls', 'sendmail' => '/usr/sbin/sendmail -bs'])->andReturnNull()
            ->shouldReceive('put')->once()->with('email.from', ['name' => 'The Application', 'address' => 'crynobone@gmail.com'])->andReturnNull()
            ->shouldReceive('finish')->once()->andReturn(true);

        $this->artisan('orchestra:configure-email')
            ->expectsQuestion('What is the application name?', 'The Application')
            ->expectsQuestion('What is the e-mail address?', 'crynobone@gmail.com')
            ->assertExitCode(0);
    }
}
