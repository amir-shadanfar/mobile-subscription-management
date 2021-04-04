<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\OsCredential;
use Faker\Generator as Faker;

$factory->define(OsCredential::class, function (Faker $faker) {
    return [
        'os'       => $faker->randomElement(\App\Enums\OsEnum::toArray()),
        'username' => $faker->userName,
        'password' => $faker->password,
    ];
});
