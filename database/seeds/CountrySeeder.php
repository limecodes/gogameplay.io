<?php

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countriesPath = realpath(__DIR__.'/../../mcc-mnc-table.json');
        $countries = json_decode(file_get_contents($countriesPath));

        $uniqueCountries = [];

        foreach ($countries as $country) {
        	if (array_key_exists($country->country, $uniqueCountries) === false) {
        		$uniqueCountries[$country->country] = $country->iso;
        	}
        }

        foreach ($uniqueCountries as $key => $value) {
        	DB::Table('countries')->insert([
        		'name' => $key,
        		'iso_code' => $value
        	]);
        }
    }
}
