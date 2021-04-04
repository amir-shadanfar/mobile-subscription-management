<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Device;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Device::class, function (Faker $faker) {

    return [
        'uid'                      => Str::random(16),
        'language'                 => $faker->languageCode,
        'os'                       => $faker->randomElement(\App\Enums\OsEnum::toArray()),
        'token'                    => sha1(Str::random(60) . time()),
        'created_at'               => $faker->dateTime(),
        'updated_at'               => $faker->dateTime(),
    ];

});
