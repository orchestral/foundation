<?php

namespace Orchestra\Tests\Feature\Processor\Account;

use Mockery as m;
use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Testing\Installation;
use Orchestra\Foundation\Processors\Account\ProfileCreator;

class ProfileCreatorTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_can_show_create_page()
    {
        $stub = $this->app->make(ProfileCreator::class);

        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator');
        $listener->shouldReceive('showProfileCreator')->once()
            ->with(m::type('Array'))->andReturn('profile.create');

        $this->assertEquals('profile.create', $stub->create($listener));
    }

    /** @test */
    public function it_can_store_profile()
    {
        $presenter = $this->app->make('Orchestra\Foundation\Http\Presenters\Account');
        $validator = $this->app->make('Orchestra\Foundation\Validation\Account');

        $stub = m::mock(ProfileCreator::class.'[notifyCreatedUser]', [
            $presenter, $validator,
        ])->shouldAllowMockingProtectedMethods();

        $stub->shouldReceive('notifyCreatedUser')->once()
            ->andReturnUsing(function ($listener, $user, $password) {
                return $listener->profileCreated();
            });

        $data = [
            'email' => 'email@orchestraplatform.com',
            'fullname' => 'Administrator',
            'password' => 'secret',
        ];

        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator');
        $listener->shouldReceive('profileCreated')->once()->andReturn('profile.created');

        $this->assertEquals('profile.created', $stub->store($listener, $data));

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'fullname' => $data['fullname'],
        ]);
    }

    /** @test */
    public function it_can_store_profile_without_sending_notification()
    {
        $presenter = $this->app->make('Orchestra\Foundation\Http\Presenters\Account');
        $validator = $this->app->make('Orchestra\Foundation\Validation\Account');

        $stub = m::mock(ProfileCreator::class.'[notifyCreatedUser]', [
            $presenter, $validator,
        ])->shouldAllowMockingProtectedMethods();

        $stub->shouldReceive('notifyCreatedUser')->once()
            ->andReturnUsing(function ($listener, $user, $password) {
                return $listener->profileCreatedWithoutNotification();
            });

        $data = [
            'email' => 'email@orchestraplatform.com',
            'fullname' => 'Administrator',
            'password' => 'secret',
        ];

        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator');
        $listener->shouldReceive('profileCreatedWithoutNotification')->once()
            ->andReturn('profile.created.without.notification');

        $this->assertEquals('profile.created.without.notification', $stub->store($listener, $data));

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'fullname' => $data['fullname'],
        ]);
    }

    /** @test */
    public function it_cant_store_profile_given_database_fails()
    {
        $presenter = $this->app->make('Orchestra\Foundation\Http\Presenters\Account');
        $validator = $this->app->make('Orchestra\Foundation\Validation\Account');

        $stub = m::mock(ProfileCreator::class.'[saving]', [
            $presenter, $validator,
        ])->shouldAllowMockingProtectedMethods();

        $stub->shouldReceive('saving')->once()->andThrow('\Exception');

        $data = [
            'email' => 'email@orchestraplatform.com',
            'fullname' => 'Administrator',
            'password' => 'secret',
        ];

        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator');
        $listener->shouldReceive('createProfileFailed')->once()
            ->with(m::type('Array'))->andReturn('profile.failed');

        $this->assertEquals('profile.failed', $stub->store($listener, $data));

        $this->assertDatabaseMissing('users', [
            'email' => $data['email'],
            'fullname' => $data['fullname'],
        ]);
    }

    /**
     * Test Orchestra\Foundation\Processors\Account\ProfileCreator::store()
     * method with failed validation.
     *
     * @test
     */
    public function it_cant_store_profile_given_failed_validation()
    {
        $stub = $this->app->make(ProfileCreator::class);

        $data = [
            'email' => 'email[at]orchestraplatform.com',
            'fullname' => 'Administrator',
            'password' => 'secret',
        ];

        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator');
        $listener->shouldReceive('createProfileFailedValidation')->once()
            ->with(m::type('Illuminate\Support\MessageBag'))->andReturn('profile.failed.validation');

        $this->assertEquals('profile.failed.validation', $stub->store($listener, $data));

        $this->assertDatabaseMissing('users', [
            'email' => $data['email'],
            'fullname' => $data['fullname'],
        ]);
    }
}
