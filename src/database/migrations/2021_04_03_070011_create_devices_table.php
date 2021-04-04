<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 255);
            $table->string('language', 3);
            $table->enum('os', \App\Enums\OsEnum::toArray());
            $table->text('token')->nullable();
            $table->timestamps();
        });

        // pivot table
        Schema::create('applications_devices', function (Blueprint $table) {
            $table->primary(['application_id', 'device_id']);
            $table->unsignedBigInteger('application_id')->index();
            $table->unsignedBigInteger('device_id')->index();
            $table->enum('subscription_status', \App\Enums\SubscriptionStatusEnum::toArray())->nullable();
            $table->timestamp('subscription_expire_date')->nullable();
            $table->timestamps();

            $table->foreign('application_id')->references('id')->on('applications');
            $table->foreign('device_id')->references('id')->on('devices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications_devices');
        Schema::dropIfExists('devices');
    }
}
