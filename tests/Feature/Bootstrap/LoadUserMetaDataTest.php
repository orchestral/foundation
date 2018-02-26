<?php

namespace Orchestra\Tests\Feature\Bootstrap;

use Orchestra\Tests\Feature\TestCase;

class LoadUserMetaDataTest extends TestCase
{
    /** @test */
    public function it_can_create_user_meta()
    {
        $stub = $this->app->make('orchestra.memory')->driver('user');

        $this->assertInstanceOf('\Orchestra\Model\Memory\UserProvider', $stub);
        $this->assertInstanceOf('\Orchestra\Memory\Provider', $stub);
    }
}
