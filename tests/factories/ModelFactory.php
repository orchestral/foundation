<?php

use Faker\Generator;
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
        'password' => '$2y$10$ljMS3rXiNnM33rTw.UadO.jJ5xFwUzidc0pzMkJSmbBykB0.aAv0y',
        'remember_token' => str_random(10),
        'status' => User::VERIFIED,
    ];
});

$factory->defineAs(User::class, 'admin', function (Generator $faker) {
    return [
        'email' => $faker->safeEmail,
        'fullname' => $faker->name,
        'password' => '$2y$10$ljMS3rXiNnM33rTw.UadO.jJ5xFwUzidc0pzMkJSmbBykB0.aAv0y',
        'remember_token' => str_random(10),
        'status' => User::VERIFIED,
    ];
});
