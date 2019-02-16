<?php

namespace Orchestra\Tests\Controller\Users;

use Mockery as m;
use Orchestra\Tests\Controller\TestCase;
use Orchestra\Foundation\Testing\Installation;
use Orchestra\Foundation\Processors\User as UserProcessor;

class EditorTest extends TestCase
{
    use Installation;

    /** @test */
    public function its_not_accessible_for_user()
    {
        $user = $this->createUserAsMember();

        $second = $this->createUserAsMember();

        $this->actingAs($user)
            ->visit("admin/users/{$second->id}/edit")
            ->seePageIs('admin');
    }

    /** @test */
    public function its_can_accessible_for_admin()
    {
        $second = $this->createUserAsMember();

        $this->actingAs($this->adminUser)
            ->visit("admin/users/{$second->id}/edit")
            ->seePageIs("admin/users/{$second->id}/edit")
            ->seeText('Edit User');
    }

    /** @test */
    public function its_can_edit_a_user()
    {
        $second = $this->createUserAsMember();

        $this->actingAs($this->adminUser)
            ->visit("admin/users/{$second->id}/edit")
            ->type('crynobone@gmail.com', 'email')
            ->type('Mior Muhammad Zaki', 'fullname')
            ->select('2', 'roles[]')
            ->press('Submit')
            ->seeText('User has been updated')
            ->seePageIs('admin/users');

        $second->refresh();

        $this->assertSame('crynobone@gmail.com', $second->email);
        $this->assertSame('Mior Muhammad Zaki', $second->fullname);
    }

    /** @test */
    public function its_cant_edit_a_user_due_to_database_errors()
    {
        $this->instance(UserProcessor::class, $processor = m::mock(UserProcessor::class.'[saving]', [
            $this->app->make(\Orchestra\Foundation\Http\Presenters\User::class),
            $this->app->make(\Orchestra\Foundation\Validations\User::class),
        ]))->shouldAllowMockingProtectedMethods();

        $second = $this->createUserAsMember();

        $this->actingAs($this->adminUser)
            ->visit("admin/users/{$second->id}/edit")
            ->type('crynobone@gmail.com', 'email')
            ->type('Mior Muhammad Zaki', 'fullname')
            ->select('2', 'roles[]')
            ->press('Submit')
            ->dontSeeText('User has been updated')
            ->seePageIs('admin/users');

        $second->refresh();

        $this->assertNotSame('crynobone@gmail.com', $second->email);
        $this->assertNotSame('Mior Muhammad Zaki', $second->fullname);
    }

    /** @test */
    public function its_cant_edit_a_user_due_to_validation_fails()
    {
        $second = $this->createUserAsMember();

        $this->actingAs($this->adminUser)
            ->visit("admin/users/{$second->id}/edit")
            ->type('crynobone[at]gmail.com', 'email')
            ->type('Mior Muhammad Zaki', 'fullname')
            ->select('2', 'roles[]')
            ->press('Submit')
            ->seeText('The email must be a valid email address.')
            ->dontSeeText('User has been updated')
            ->seePageIs("admin/users/{$second->id}/edit");

        $second->refresh();

        $this->assertNotSame('crynobone[at]gmail.com', $second->email);
        $this->assertNotSame('Mior Muhammad Zaki', $second->fullname);
    }

    /** @test */
    public function its_cant_edit_a_user_and_send_for_a_different_user()
    {
        $this->expectException('Laravel\BrowserKitTesting\HttpException');

        $second = $this->createUserAsMember();

        $this->actingAs($this->adminUser)
            ->visit("admin/users/{$second->id}/edit")
            ->makeRequest('POST', "admin/users/{$second->id}", [
                'id' => 'foo',
                'email' => 'email@orchestraplatform.com',
                'fullname' => 'Administrator',
                'password' => '123456',
                'roles' => [1],
            ])
            ->seeText('User has been updated')
            ->seePageIs("admin/users/{$second->id}/edit");
    }
}
