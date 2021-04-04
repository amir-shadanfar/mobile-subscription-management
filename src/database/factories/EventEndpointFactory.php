<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\EventEndpoint;
use Faker\Generator as Faker;

$factory->define(EventEndpoint::class, function (Faker $faker) {

    return [
        'event_type' => $faker->randomElement(\App\Enums\SubscriptionStatusEnum::toArray()),
        'url'        => $faker->url,
    ];

});
