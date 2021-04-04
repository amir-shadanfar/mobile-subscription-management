<?php

use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * @param \Faker\Generator $faker
     */
    public function run(Faker\Generator $faker)
    {

        factory(\App\Application::class, 2)->create()->each(function ($application) use ($faker) {

            // create devices & determine belong to which app
            /**
            factory(\App\Device::class, 10)->create()->each(function ($device) use ($application, $faker) {
                $application->devices()->attach($device->id, [
                    'subscription_status'      => array_rand(\App\Enums\SubscriptionStatusEnum::toArray()),
                    'subscription_expire_date' => $faker->dateTimeThisMonth(),
                    'created_at'               => \Carbon\Carbon::now(),
                    'updated_at'               => \Carbon\Carbon::now(),
                ]);
            });
             */

            // create eventEndpoint for app
            foreach (\App\Enums\SubscriptionStatusEnum::toArray() as $event) {
                $eventEndpoint = factory(App\EventEndpoint::class)->make([
                    'event_type' => $event
                ]);
                $application->eventEndpoints()->save($eventEndpoint);
            }

            // create osCredentials for app
            foreach (\App\Enums\OsEnum::toArray() as $os) {
                $osCredential = factory(App\OsCredential::class)->make([
                    'os' => $os
                ]);
                $application->osCredentials()->save($osCredential);
            }

        });

    }
}
