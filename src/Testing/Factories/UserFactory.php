<?php

namespace Orchestra\Foundation\Testing\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Orchestra\Foundation\Auth\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->safeEmail,
            'fullname' => $this->faker->name,
            'password' => '$2y$04$Ri4Tj1yi9EnO6EI3lS11suHnymOKbC63D85NeHHo74uk4dHe9eah2',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'status' => User::VERIFIED,
        ];
    }

    /**
     * Configure the model factory for member state.
     *
     * @return void
     */
    public function member()
    {
        return $this->state(function ($attributes) {
            return [];
        })->afterCreating(function (User $user) {
            $user->roles()->sync([2]);
        });
    }

    /**
     * Configure the model factory for admin state.
     *
     * @return void
     */
    public function admin()
    {
        return $this->state(function ($attributes) {
            return [];
        })->afterCreating(function (User $user) {
            $user->roles()->sync([1]);
        });
    }
}
