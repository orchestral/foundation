<?php

namespace Orchestra\Tests\Controller\Users;

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
            ->visit('admin/users')
            ->seePageIs('admin');
    }

    /** @test */
    public function its_can_access_list_of_users()
    {
        $this->actingAs($this->adminUser)
            ->visit('admin/users')
            ->seePageIs('admin/users')
            ->seeText('Users')
            ->seeLink('Add', 'admin/users/create');
    }
}
