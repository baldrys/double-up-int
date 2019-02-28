<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(App\UserProfile::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            // Случайный id из таблицы users
            return App\User::inRandomOrder()->first()->id;
        },
        'name' => $faker->userName,
    ];
});
