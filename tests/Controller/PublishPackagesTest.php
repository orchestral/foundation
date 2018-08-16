<?php

namespace Orchestra\Tests\Controller;

use Mockery as m;
use Orchestra\Foundation\Auth\User;
use Orchestra\Foundation\Testing\Installation;
use Orchestra\Foundation\Processors\AssetPublisher;
use Orchestra\Foundation\Http\Controllers\PublisherController;

class PublishPackagesTest extends TestCase
{
    use Installation;

    /** @test */
    public function its_not_accessible_for_user()
    {
        $user = $this->createUserAsMember();

        $this->actingAs($user)
            ->visit('admin/publisher/ftp')
            ->seePageIs('admin');
    }

    /** @test */
    public function it_can_select_publishing_driver()
    {
        $this->instance('orchestra.publisher.ftp', $client = m::mock('\Orchestra\Contracts\Publisher\Uploader'));

        $client->shouldReceive('connected')->once()->andReturn(false);

        $this->actingAs($this->adminUser)
            ->visit('admin/publisher')
            ->seePageIs('admin/publisher/ftp');
    }

    /** @test */
    public function it_can_publish_assets_using_ftp_connection()
    {
        $data = [
            'host' => 'localhost',
            'user' => 'ftp_user',
            'password' => 'secret',
            'connection-type' => 'ftp',
        ];

        $this->getMockedProcessor()->shouldReceive('publish')->once()
            ->with(m::type(PublisherController::class), m::type('Array'))
            ->andReturnUsing(function ($listener) {
                return $listener->publishingHasSucceed();
            });

        $this->actingAs($this->adminUser)
            ->visit('admin/publisher/ftp')
            ->type($data['host'], 'host')
            ->type($data['user'], 'user')
            ->type($data['password'], 'password')
            ->select($data['connection-type'], 'connection-type')
            ->press('Login')
            ->seePageIs('admin/publisher/ftp');
    }

    /** @test */
    public function it_cant_publish_assets_using_ftp_connection_when_connection_fails()
    {
        $data = [
            'host' => 'localhost',
            'user' => 'ftp_user',
            'password' => 'secret',
            'connection-type' => 'ftp',
        ];

        $this->getMockedProcessor()->shouldReceive('publish')->once()
            ->with(m::type(PublisherController::class), m::type('Array'))
            ->andReturnUsing(function ($listener) {
                return $listener->publishingHasFailed(['error' => 'Invalid user credential!']);
            });

        $this->actingAs($this->adminUser)
            ->visit('admin/publisher/ftp')
            ->type($data['host'], 'host')
            ->type($data['user'], 'user')
            ->type($data['password'], 'password')
            ->select($data['connection-type'], 'connection-type')
            ->press('Login')
            ->seeText('Invalid user credential!')
            ->seePageIs('admin/publisher/ftp');
    }

    /**
     * Get processor mock.
     *
     * @return \Orchestra\Foundation\Processors\AssetPublisher
     */
    protected function getMockedProcessor()
    {
        $processor = m::mock(AssetPublisher::class, [
            m::mock('\Orchestra\Foundation\Publisher\PublisherManager'),
            m::mock('\Illuminate\Session\Store'),
        ]);

        $this->instance(AssetPublisher::class, $processor);

        return $processor;
    }
}
