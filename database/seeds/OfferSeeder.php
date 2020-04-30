<?php

use Illuminate\Database\Seeder;

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
	        'carrier' => 'Vodafone',
	        'url' => 'http://www.example.com',
	        'type' => 1
    	]);
    }
}
