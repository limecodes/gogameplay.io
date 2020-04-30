<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\ConnectionRequest;
use App\Http\Resources\ConnectionResource;
use App\Http\Resources\CarrierResource;
use App\Models\Visitor;
use App\Models\Country;

class ConnectionController extends Controller
{
	// TODO: ! Code duplication !
	private function fetchLocationApi($ipAddress)
	{
		$apiResponse = Http::get(env('IP2LOCATION_BASE_URL').'?ip='.$ipAddress.'&key='.env('IP2LOCATION_API_KEY').'&package=WS19');
	
		$apiResponseData = $apiResponse->json();

		return [
			'iso_code' => $apiResponseData['country_code'],
			'carrier' => ($apiResponseData['mobile_brand'] !== '-') ? $apiResponseData['mobile_brand'] : false
		];
	}

	private function updateAndroidConnection($ipAddress, $uid)
	{
		$visitor = Visitor::where('uid', $uid)->first();

		$visitor->ip_address = $ipAddress;
		$visitor->mobile_connection = true;

		if ( (!$visitor->country_id) && (!$visitor->carrier_from_data) ) {
			$locationData = $this->fetchLocationApi($ipAddress);

			$visitor->country_id = Country::where('iso_code', $locationData['iso_code'])->first()->id;
			$visitor->carrier_from_data = ($locationData['carrier']) ? $locationData['carrier'] : null;
		}

		$visitor->save();

		if ( ($visitor->mobile_connection == true) && ($visitor->carrier_from_data == null) ) {
			$ret = [
				'visitor' => new ConnectionResource($visitor),
				'carriers_by_country' => CarrierResource::collection($visitor->country->mobileNetwork)
			];
		} else {
			$ret = new ConnectionResource($visitor);
		}

		return $ret;
	}

	private function updateAppleConnection($ipAddress, $uid)
	{
		$visitor = Visitor::where('uid', $uid)->first();

		if ($visitor->ip_address !== $ipAddress) {
			// Connection did in fact change
			$visitor->ip_address = $ipAddress;

			$locationData = $this->fetchLocationApi($ipAddress);

			$visitor->mobile_connection = ($locationData['carrier']) ? true : false;
			$visitor->carrier_from_data = ($locationData['carrier']) ? $locationData['carrier'] : null;

			$visitor->save();
		}

		if (!$visitor->mobile_connection) {
			$ret = [
				'visitor' => new ConnectionResource($visitor),
				'carriers_by_country' => CarrierResource::collection($visitor->country->mobileNetwork)
			];
		} else {
			$ret = new ConnectionResource($visitor);
		}

		return $ret;
	}

    public function connectionChanged(ConnectionRequest $request)
    {
    	$connectionRequestValidated = $request->validated();

    	$ipAddress = $request->server('GGP_REMOTE_ADDR');
    	$uid = $connectionRequestValidated['uid'];

    	if ($connectionRequestValidated['device'] == 'android') {
    		$response = $this->updateAndroidConnection($ipAddress, $uid);
    	} else if ($connectionRequestValidated['device'] == 'ios') {
    		$response = $this->updateAppleConnection($ipAddress, $uid);
    	}

 		return response()->json($response, 200);
    }
}
