<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class CreateVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->ipAddress('ip_address');
            $table->enum('device',[
                Config::get('constants.devices.non_mobile'),
                Config::get('constants.devices.android'),
                Config::get('constants.devices.ios')
            ]);
            $table->foreignId('country_id')->nullable();
            $table->boolean('mobile_connection')->nullable();
            $table->string('carrier_from_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitors');
    }
}
