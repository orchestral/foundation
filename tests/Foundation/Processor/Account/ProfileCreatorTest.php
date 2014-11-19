<?php namespace Orchestra\Foundation\Processor\Account\TestCase;

use Mockery as m;
use Orchestra\Foundation\Processor\Account\ProfileCreator;

class ProfileCreatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }
}
