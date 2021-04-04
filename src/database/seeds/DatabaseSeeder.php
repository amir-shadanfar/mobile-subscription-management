<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('applications_devices')->truncate();
        DB::table('applications')->truncate();
        DB::table('devices')->truncate();
        DB::table('event_endpoints')->truncate();
        DB::table('os_credentials')->truncate();

        $this->call(ApplicationSeeder::class);
        $this->call(DeviceSeeder::class);
        $this->call(EventEndpointSeeder::class);
        $this->call(OsCredentialSeeder::class);
    }
}
