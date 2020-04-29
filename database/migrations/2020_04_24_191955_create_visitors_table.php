<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->ipAddress('ip_address')->unique();
            $table->string('device', 255)->nullable();
            $table->foreignId('country_id')->nullable();
            $table->boolean('mobile_connection')->nullable();
            $table->string('carrier_from_data')->nullable();
            $table->foreignId('mobile_network_id')->nullable();
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
