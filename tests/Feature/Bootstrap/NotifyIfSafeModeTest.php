<?php

namespace Orchestra\Tests\Feature\Bootstrap;

use Mockery as m;
use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Bootstrap\NotifyIfSafeMode;

class NotifyIfSafeModeTest extends TestCase
{
    /** @test */
    public function it_show_notification_when_on_safe_mode()
    {
        $this->instance('orchestra.extension.status', $mode = m::mock('\Orchestra\Contracts\Extension\StatusChecker'));
        $this->instance('orchestra.messages', $messages = m::mock('\Orchestra\Contracts\Messages\MessageBag'));

        $mode->shouldReceive('is')->once()->with('safe')->andReturn(true);

        $messages->shouldReceive('extend')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($messages) {
                    return $c($messages);
                })
            ->shouldReceive('add')->once()->with('info', m::type('String'))->andReturnNull();

        $this->assertNull((new NotifyIfSafeMode())->bootstrap($this->app));
    }
}
