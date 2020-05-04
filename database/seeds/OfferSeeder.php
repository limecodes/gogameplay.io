<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::Table('offers')->insert([
    		'name' => 'Test Game Offer',
	        'text' => null,
	        'country_id' => 1,
            'device' => Config::get('constants.devices.any'),
	        'carrier' => 'Vodafone',
	        'url' => 'http://www.example.com',
	        'type' => 1
    	]);
    }
}
