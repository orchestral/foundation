<?php

use Faker\Generator;
use Illuminate\Support\Str;
use Orchestra\Foundation\Auth\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(User::class, function (Generator $faker) {
    return [
        'email' => $faker->safeEmail,
        'fullname' => $faker->name,
        'password' => '$2y$04$Ri4Tj1yi9EnO6EI3lS11suHnymOKbC63D85NeHHo74uk4dHe9eah2',
        'email_verified_at' => now(),
        'remember_token' => Str::random(10),
        'status' => User::VERIFIED,
    ];
});

$factory->defineAs(User::class, 'admin', function (Generator $faker) {
    return [
        'email' => $faker->safeEmail,
        'fullname' => $faker->name,
        'password' => '$2y$04$Ri4Tj1yi9EnO6EI3lS11suHnymOKbC63D85NeHHo74uk4dHe9eah2',
        'email_verified_at' => now(),
        'remember_token' => Str::random(10),
        'status' => User::VERIFIED,
    ];
});
