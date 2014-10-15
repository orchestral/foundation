<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Testing\TestCase;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Publisher;

class PublisherControllerTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        View::shouldReceive('share')->once()->with('errors', m::any());
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test GET /admin/publisher
     *
     * @test
     */
    public function testGetIndexAction()
    {
        Publisher::shouldReceive('connected')->once()->andReturn(true);
        Publisher::shouldReceive('execute')->once()->andReturn(true);

        $this->call('GET', 'admin/publisher');
        $this->assertRedirectedTo(handles('orchestra::publisher/ftp'));
    }

    /**
     * Test GET /admin/publisher/ftp
     *
     * @test
     */
    public function testGetFtpAction()
    {
        View::shouldReceive('make')->once()->with('orchestra/foundation::publisher.ftp')->andReturn('foo');

        $this->call('GET', 'admin/publisher/ftp');
        $this->assertResponseOk();
    }

    /**
     * Test POST /admin/publisher/ftp
     *
     * @test
     */
    public function testPostFtpAction()
    {
        $input = array(
            'host'     => 'localhost',
            'username' => 'foo',
            'password' => 'foobar',
        );

        Publisher::shouldReceive('connect')->once()->andReturn(true);
        Publisher::shouldReceive('queued')->once()->andReturn(array('laravel/framework'));
        Publisher::shouldReceive('connected')->once()->andReturn(true);
        Publisher::shouldReceive('execute')->once()->andReturn(true);

        $input['connection-type'] = 'ftp';

        $this->call('POST', 'admin/publisher/ftp', $input);
        $this->assertRedirectedTo(handles('orchestra::publisher/ftp'));
    }

    /**
     * Test POST /admin/publisher/ftp when FTP connect failed.
     *
     * @test
     */
    public function testPostFtpActionWhenFtpConnectFailed()
    {
        $input = array(
            'host'     => 'localhost',
            'username' => 'foo',
            'password' => 'foobar',
        );

        Publisher::shouldReceive('connect')->once()->andThrow('\Orchestra\Support\Ftp\ServerException');
        Publisher::shouldReceive('queued')->once()->andReturn(array('laravel/framework'));
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturn(true);

        $input['connection-type'] = 'ftp';

        $this->call('POST', 'admin/publisher/ftp', $input);
        $this->assertRedirectedTo(handles('orchestra::publisher/ftp'));
    }
}
