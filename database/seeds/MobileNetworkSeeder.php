<?php

use Illuminate\Database\Seeder;

class MobileNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $networksPath = realpath(__DIR__.'/../../mcc-mnc-table.json');
        $networks = json_decode(file_get_contents($networksPath));

        foreach ($networks as $network) {

        	if ($network->iso !== "n/a") {
        		$country = DB::Table('countries')->where('iso_code', $network->iso)->first();
        		$countryId = $country->id;
        	} else {
        		$countryId = null;
        	}

        	DB::Table('mobile_networks')->insert([
        		'name' => $network->network,
        		'mcc' => ($network->mcc !== "n/a") ? $network->mcc : null,
        		'mnc' => ($network->mnc !== "n/a") ? $network->mnc : null,
        		'country_id' => $country->id
        	]);
        }
    }
}
