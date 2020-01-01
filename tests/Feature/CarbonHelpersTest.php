<?php

namespace Orchestra\Tests\Feature;

use Carbon\Carbon;
use Mockery as m;
use Orchestra\Foundation\Testing\Installation;

class CarbonHelpersTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2019-01-01 16:45:10', 'UTC'));
    }

    /** @test */
    public function it_can_use_carbonize_helper()
    {
        $date = \carbonize('now');

        $this->assertSame('UTC', (string) $date->timezone);
        $this->assertSame('2019-01-01 16:45:10', (string) $date->toDateTimeString());
    }

    /** @test */
    public function it_can_use_carbonize_helper_from_carbon_instance()
    {
        $date = \carbonize(Carbon::today());

        $this->assertSame('UTC', (string) $date->timezone);
        $this->assertSame('2019-01-01 00:00:00', (string) $date->toDateTimeString());
    }

    /** @test */
    public function it_can_use_carbonize_helper_from_datetime_instance()
    {
        $date = \carbonize(new \DateTime('2019-02-01'));

        $this->assertSame('UTC', (string) $date->timezone);
        $this->assertSame('2019-02-01 00:00:00', (string) $date->toDateTimeString());
    }

    /** @test */
    public function it_can_use_carbonize_helper_when_parsing_from_array()
    {
        $date = \carbonize(['date' => '2019-01-02 01:23:06', 'timezone' => 'Asia/Kuala_Lumpur']);

        $this->assertSame('Asia/Kuala_Lumpur', (string) $date->timezone);
        $this->assertSame('2019-01-02 01:23:06', (string) $date->toDateTimeString());
    }

    /** @test */
    public function it_cant_use_carbonize_helper_when_parsing_invalid_date()
    {
        $date = \carbonize('foo');

        $this->assertNull(\carbonize('foo'));
    }

    /** @test */
    public function it_can_use_timezone_helper()
    {
        $date = Carbon::now('UTC');

        $duplicate = \use_timezone($date, 'UTC');

        $this->assertNotSame($duplicate, $date);
        $this->assertSame('UTC', (string) $date->timezone);
        $this->assertSame('UTC', (string) $duplicate->timezone);
        $this->assertSame($date->toDateTimeString(), $duplicate->toDateTimeString());
        $this->assertSame('2019-01-01 16:45:10', $duplicate->toDateTimeString());

        $duplicateTimezone = \use_timezone($date, 'Asia/Kuala_Lumpur');
        $this->assertNotSame($duplicateTimezone, $date);
        $this->assertSame('UTC', (string) $date->timezone);
        $this->assertSame('Asia/Kuala_Lumpur', (string) $duplicateTimezone->timezone);
        $this->assertSame('2019-01-02 00:45:10', $duplicateTimezone->toDateTimeString());
    }
}
