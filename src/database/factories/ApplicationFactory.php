<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Application;
use \Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Application::class, function (Faker $faker) {

    $name = $faker->name;

    return [
        'title' => $name,
        'code'  => Str::random(20)
    ];

});

