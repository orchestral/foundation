<?php

namespace Orchestra\Tests\Feature\Processor\Account;

use Mockery as m;
use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Testing\Installation;
use Orchestra\Foundation\Processors\Account\ProfileUpdater;

class ProfileUpdaterTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_can_show_edit_page()
    {
        $this->actingAs($user = $this->createUserAsMember());

        $stub = $this->app->make(ProfileUpdater::class);

        $listener = m::mock('Orchestra\Contracts\Foundation\Listener\Account\ProfileUpdater');
        $listener->shouldReceive('showProfileChanger')->once()->andReturn('show.profile.changer');

        $this->assertEquals('show.profile.changer', $stub->edit($listener));
    }

    /** @test */
    public function it_can_update_the_profile()
    {
        $this->actingAs($user = $this->createUserAsMember());

        $stub = $this->app->make(ProfileUpdater::class);

        $listener = m::mock('Orchestra\Contracts\Foundation\Listener\Account\ProfileUpdater');
        $listener->shouldReceive('profileUpdated')->once()->andReturn('profile.updated');

        $this->assertEquals('profile.updated', $stub->update($listener, [
            'id' => $user->id,
            'email' => 'crynobone@gmail.com',
            'fullname' => 'Mior Muhammad Zaki',
        ]));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'crynobone@gmail.com',
            'fullname' => 'Mior Muhammad Zaki',
        ]);
    }

    /** @test */
    public function it_cant_update_given_invalid_user()
    {
        $this->actingAs($user = $this->createUserAsMember());

        $stub = $this->app->make(ProfileUpdater::class);

        $listener = m::mock('Orchestra\Contracts\Foundation\Listener\Account\ProfileUpdater');
        $listener->shouldReceive('abortWhenUserMismatched')->once()->andReturn('user.missmatched');

        $this->assertEquals('user.missmatched', $stub->update($listener, [
            'id' => $this->adminUser->id,
            'email' => 'crynobone@gmail.com',
            'fullname' => 'Mior Muhammad Zaki',
        ]));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
            'fullname' => $user->fullname,
        ]);
    }

    /** @test */
    public function it_cant_update_given_validation_fails()
    {
        $this->actingAs($user = $this->createUserAsMember());

        $stub = $this->app->make(ProfileUpdater::class);

        $listener = m::mock('Orchestra\Contracts\Foundation\Listener\Account\ProfileUpdater');
        $listener->shouldReceive('updateProfileFailedValidation')->once()
            ->with(m::type('Illuminate\Support\MessageBag'))
            ->andReturn('profile.failed.validation');

        $this->assertEquals('profile.failed.validation', $stub->update($listener, [
            'id' => $user->id,
            'email' => 'crynobone[at]gmail.com',
            'fullname' => 'Mior Muhammad Zaki',
        ]));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
            'fullname' => $user->fullname,
        ]);
    }

    /**
     * Test Orchestra\Foundation\Processors\Account\ProfileUpdater::update()
     * method given saving failed.
     *
     * @test
     */
    public function it_cant_update_given_database_fails()
    {
        $this->actingAs($user = $this->createUserAsMember());

        $presenter = $this->app->make('Orchestra\Foundation\Http\Presenters\Account');
        $validator = $this->app->make('Orchestra\Foundation\Validations\Account');

        $stub = m::mock(ProfileUpdater::class.'[saving]', [
            $presenter, $validator,
        ])->shouldAllowMockingProtectedMethods();

        $stub->shouldReceive('saving')->once()->andThrows('\Exception');

        $listener = m::mock('Orchestra\Contracts\Foundation\Listener\Account\ProfileUpdater');
        $listener->shouldReceive('updateProfileFailed')->once()
            ->with(m::type('Array'))->andReturn('profile.failed');

        $this->assertEquals('profile.failed', $stub->update($listener, [
            'id' => $user->id,
            'email' => 'crynobone@gmail.com',
            'fullname' => 'Mior Muhammad Zaki',
        ]));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
            'fullname' => $user->fullname,
        ]);
    }
}
