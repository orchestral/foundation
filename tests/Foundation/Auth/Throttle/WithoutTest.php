<?php

namespace Orchestra\Foundation\TestCase\Auth\Throttle;

use PHPUnit\Framework\TestCase;
use Orchestra\Foundation\Auth\Throttle\Without as WithoutThrottle;

class WithoutTest extends TestCase
{
    public function testHasTooManyLoginAttemptsMethod()
    {
        $stub = new WithoutThrottle();

        $this->assertFalse($stub->hasTooManyLoginAttempts([]));
    }

    public function testGetSecondsBeforeNextAttemptsMethod()
    {
        $stub = new WithoutThrottle();

        $this->assertEquals(60, $stub->getSecondsBeforeNextAttempts([]));
    }

    public function testIncrementLoginAttemptsMethod()
    {
        $stub = new WithoutThrottle();

        $this->assertNull($stub->incrementLoginAttempts([]));
    }

    public function testClearLoginAttemptsMethod()
    {
        $stub = new WithoutThrottle();

        $this->assertNull($stub->clearLoginAttempts([]));
    }
}
