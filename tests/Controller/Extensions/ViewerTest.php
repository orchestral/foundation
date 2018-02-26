<?php

namespace Orchestra\Tests\Controller\Extensions;

use Orchestra\Tests\Controller\TestCase;
use Orchestra\Foundation\Testing\Installation;

class ViewerTest extends TestCase
{
    use Installation;

    /** @test */
    public function its_not_accessible_for_user()
    {
        $user = $this->createUserAsMember();

        $this->actingAs($user)
            ->visit('admin/extensions')
            ->seePageIs('admin');
    }

    /** @test */
    public function its_can_access_list_of_available_extensions()
    {
        $this->actingAs($this->adminUser)
            ->visit('admin/extensions')
            ->seePageIs('admin/extensions')
            ->seeText('Extensions');
    }
}
