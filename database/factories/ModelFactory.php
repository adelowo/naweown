<?php

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

$factory->define(Naweown\User::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'bio' => "random shit",
        'moniker' => $faker->username,
        'remember_token' => str_random(10),
        'is_email_validated' => \Naweown\User::EMAIL_UNVALIDATED
    ];
});
