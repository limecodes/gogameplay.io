<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('text')->nullable();
            $table->string('img')->nullable();
            $table->foreignId('country_id')->nullable();
            $table->enum('device', [
                Config::get('constants.devices.non_mobile'),
                Config::get('constants.devices.android'),
                Config::get('constants.devices.ios'),
                Config::get('constants.devices.any')
            ]);
            $table->string('carrier')->nullable();
            $table->string('url');
            $table->enum('type', ['main', 'backup']);
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
        Schema::dropIfExists('offers');
    }
}
