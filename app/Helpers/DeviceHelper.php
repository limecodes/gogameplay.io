<?php

namespace App\Helpers;

use App\Contracts\DeviceHelperInterface;
use App\Facades\LocationApi;
use App\Models\Visitor;

class DeviceHelper implements DeviceHelperInterface {
	
	public function getDataAndroid(Visitor $visitor):Visitor
	{
		if ( ($visitor->mobile_connection) && (!$visitor->country_id) ) {
			$locationData = LocationApi::getCountryAndDetectCarrier($visitor->ip_address);

			$visitor->country_id = $locationData['country_id'];
			$visitor->carrier_from_data = $locationData['carrier'];
		}

		return $visitor;
	}

	public function getDataApple(Visitor $visitor):Visitor
	{
		if (!$visitor->country_id) {
			$locationData = LocationApi::getCountryAndDetectCarrier($visitor->ip_address);

			$visitor->country_id = $locationData['country_id'];
			$visitor->carrier_from_data = $locationData['carrier'];
			$visitor->mobile_connection = $locationData['connection'];
		}

		return $visitor;
	}

	public function getDataNonMobile(Visitor $visitor):Visitor
	{
		$visitor->country_id = LocationApi::getCountryOnly($visitor->ip_address)['country_id'];

		return $visitor;
	}
}
