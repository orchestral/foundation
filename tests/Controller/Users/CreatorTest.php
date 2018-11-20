<?php

namespace Orchestra\Tests\Controller\Users;

use Mockery as m;
use Orchestra\Foundation\Auth\User;
use Orchestra\Tests\Controller\TestCase;
use Orchestra\Foundation\Testing\Installation;
use Orchestra\Foundation\Processors\User as UserProcessor;

class CreatorTest extends TestCase
{
    use Installation;

    /** @test */
    public function its_not_accessible_for_user()
    {
        $user = $this->createUserAsMember();

        $this->actingAs($user)
            ->visit('admin/users/create')
            ->seePageIs('admin');
    }

    /** @test */
    public function its_can_accessible_for_admin()
    {
        $this->actingAs($this->adminUser)
            ->visit('admin/users/create')
            ->seePageIs('admin/users/create')
            ->seeText('Add User');
    }

    /** @test */
    public function its_can_create_a_user()
    {
        $this->actingAs($this->adminUser)
            ->visit('admin/users/create')
            ->type('crynobone@gmail.com', 'email')
            ->type('Mior Muhammad Zaki', 'fullname')
            ->type('secret', 'password')
            ->select('2', 'roles[]')
            ->press('Submit')
            ->seeText('User has been created')
            ->seeText('Mior Muhammad Zaki')
            ->seePageIs('admin/users');

        $this->seeInDatabase('users', [
            'email' => 'crynobone@gmail.com',
            'fullname' => 'Mior Muhammad Zaki',
        ]);

        $user = User::whereEmail('crynobone@gmail.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals(['Member'], $user->getRoles()->all());
    }

    /** @test */
    public function its_can_create_an_administrator()
    {
        $this->actingAs($this->adminUser)
            ->visit('admin/users/create')
            ->type('crynobone@gmail.com', 'email')
            ->type('Mior Muhammad Zaki', 'fullname')
            ->type('secret', 'password')
            ->select(['1', '2'], 'roles[]')
            ->press('Submit')
            ->seeText('User has been created')
            ->seeText('Mior Muhammad Zaki')
            ->seePageIs('admin/users');

        $this->seeInDatabase('users', [
            'email' => 'crynobone@gmail.com',
            'fullname' => 'Mior Muhammad Zaki',
        ]);

        $user = User::whereEmail('crynobone@gmail.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals(['Administrator', 'Member'], $user->getRoles()->all());
    }

    /** @test */
    public function its_cant_create_a_user_due_to_database_errors()
    {
        $this->instance(UserProcessor::class, $processor = m::mock(UserProcessor::class.'[saving]', [
            $this->app->make(\Orchestra\Foundation\Http\Presenters\User::class),
            $this->app->make(\Orchestra\Foundation\Validations\User::class),
        ]))->shouldAllowMockingProtectedMethods();

        $processor->shouldReceive('saving')->once()->andThrows('\Exception');

        $this->actingAs($this->adminUser)
            ->visit('admin/users/create')
            ->type('crynobone@gmail.com', 'email')
            ->type('Mior Muhammad Zaki', 'fullname')
            ->type('secret', 'password')
            ->select('2', 'roles[]')
            ->press('Submit')
            ->dontSeeText('User has been created')
            ->seePageIs('admin/users');

        $this->missingFromDatabase('users', [
            'email' => 'crynobone@gmail.com',
            'fullname' => 'Mior Muhammad Zaki',
        ]);
    }

    /** @test */
    public function its_cant_create_a_user_due_to_validation_fails()
    {
        $this->actingAs($this->adminUser)
            ->visit('admin/users/create')
            ->type('crynobone[at]gmail.com', 'email')
            ->type('Mior Muhammad Zaki', 'fullname')
            ->type('secret', 'password')
            ->select('2', 'roles[]')
            ->press('Submit')
            ->seeText('The email must be a valid email address.')
            ->dontSeeText('User has been created')
            ->seePageIs('admin/users/create');

        $this->missingFromDatabase('users', [
            'email' => 'crynobone@gmail.com',
            'fullname' => 'Mior Muhammad Zaki',
        ]);
    }
}
