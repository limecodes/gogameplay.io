<?php

namespace App\External;

use Illuminate\Support\Facades\Http;
use App\Contracts\LocationApiInterface;
use App\Models\Country;

class MockLocationApi implements LocationApiInterface {

	protected $mobileResponse;
	protected $nonMobileResponse;
	protected $responseUnknownCarrier;
	protected $countryOnlyResponse;

	public function __construct()
	{
		$this->mobileResponse = [
		    "country_code" => "GE",
		    "country_name" => "Abkhazia",
		    "region_name" => "Abkhazia",
		    "city_name" => "Abkhazia",
		    "latitude" => "00.00000",
		    "longitude" => "00.00000",
		    "zip_code" => "00000",
		    "time_zone" => "+00:00",
		    "isp" => "localhost",
		    "domain" => "mobile.gogameplay.local",
		    "net_speed" => "DSL",
		    "idd_code" => "000",
		    "area_code" => "00000",
		    "weather_station_code" => "UPXX0000",
		    "weather_station_name" => "Abkhazia",
		    "mcc" => "255",
		    "mnc" => "01",
		    "mobile_brand" => "Vodafone",
		    "elevation" => "213",
		    "usage_type" => "MOB",
		    "credits_consumed" => 18
    	];

    	$this->nonMobileResponse = [
    		"country_code" => "GE",
		    "country_name" => "Abkhazia",
		    "region_name" => "Abkhazia",
		    "city_name" => "Abkhazia",
		    "latitude" => "00.00000",
		    "longitude" => "00.0000",
		    "zip_code" => "00000",
		    "time_zone" => "+00:00",
		    "isp" => "localhost",
		    "domain" => "offer.gogameplay.local",
		    "net_speed" => "DSL",
		    "idd_code" => "000",
		    "area_code" => "000",
		    "weather_station_code" => "UPXX0000",
		    "weather_station_name" => "Abkhazia",
		    "mcc" => "-",
		    "mnc" => "-",
		    "mobile_brand" => "-",
		    "elevation" => "181",
		    "usage_type" => "COM",
    		"credits_consumed" =>18
    	];

    	$this->responseUnknownCarrier = [
		    "country_code" => "GE",
		    "country_name" => "Abkhazia",
		    "region_name" => "Abkhazia",
		    "city_name" => "Abkhazia",
		    "latitude" => "00.00000",
		    "longitude" => "00.00000",
		    "zip_code" => "00000",
		    "time_zone" => "+00:00",
		    "isp" => "localhost",
		    "domain" => "mobile.gogameplay.local",
		    "net_speed" => "DSL",
		    "idd_code" => "000",
		    "area_code" => "00000",
		    "weather_station_code" => "UPXX0000",
		    "weather_station_name" => "Abkhazia",
		    "mcc" => "255",
		    "mnc" => "01",
		    "mobile_brand" => "-",
		    "elevation" => "213",
		    "usage_type" => "MOB",
		    "credits_consumed" => 18
    	];

    	$this->countryOnlyResponse = [
    		"country_code" => "GE",
    		"credits_consumed" => 1
    	];
	}

	private function sendRequest($ipAddress) {
		switch ($ipAddress) {
			case '1.1.1.1':
				return $this->nonMobileResponse;
				break;
			case '1.1.1.2':
				return $this->mobileResponse;
				break;
			case '1.1.1.3':
				return $this->responseUnknownCarrier;
				break;
			default:
				return $this->nonMobileResponse;
				break;
		}
	}

	private function sendRequestCountryOnly() {
		return $this->countryOnlyResponse;
	}

	public function getCountryAndDetectCarrier($ipAddress):array
	{
		$data = $this->sendRequest($ipAddress);

		return [
			'country_id' => Country::getCountryId($data['country_code']),
			'carrier' => ($data['mobile_brand'] !== '-') ? $data['mobile_brand'] : null
		];
	}

	public function getCountryOnly($ipAddress):array
	{
		$data = $this->sendRequestCountryOnly();

		return [
			'country_id' => Country::getCountryId($data['country_code'])
		];
	}

}