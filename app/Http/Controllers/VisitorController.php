<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\VisitorRequest;
use App\Http\Resources\VisitorResource;
use App\Http\Resources\CarrierResource;
use App\Models\Visitor;
use App\Models\Country;
use App\Models\MobileNetwork;


class VisitorController extends Controller
{
	private function fetchLocationApi($ipAddress)
	{
		$apiResponse = Http::get(env('IP2LOCATION_BASE_URL').'?ip='.$ipAddress.'&key='.env('IP2LOCATION_API_KEY').'&package=WS19');

		$apiResponseData = $apiResponse->json();

		return [
			'iso_code' => $apiResponseData['country_code'],
			'carrier' => ($apiResponseData['mobile_brand'] !== '-') ? $apiResponseData['mobile_brand'] : false
		];
	}

	private function recordOrFetchVisitor($ipAddress, $device)
	{
		$visitor = Visitor::firstOrCreate(
			['ip_address' => $ipAddress, 'device' => $device],
			['uid' => (string) Str::uuid(), 'ip_address' => $ipAddress, 'device' => $device]
		);

		return $visitor;
	}

	private function androidSet($connection, $ipAddress)
	{
		$visitor = $this->recordOrFetchVisitor($ipAddress, 'android');

		$visitor->mobile_connection = $connection;

		if ( ($connection) && (!$visitor->country_id) ) {

			$locationData = $this->fetchLocationApi($ipAddress);

			$visitor->country_id = Country::where('iso_code', $locationData['iso_code'])->first()->id;
			$visitor->carrier_from_data = ($locationData['carrier']) ? $locationData['carrier'] : null;
		}

		$visitor->save();

		if ( ($visitor->mobile_connection == true) && ($visitor->carrier_from_data == null) ) {
			$ret = [
				'visitor' => new VisitorResource($visitor),
				'carriers_by_country' => CarrierResource::collection($visitor->country->mobileNetwork)
			];
		} else {
			$ret = new VisitorResource($visitor);
		}

		return $ret;
	}

	private function appleSet($ipAddress)
	{
		$visitor = $this->recordOrFetchVisitor($ipAddress, 'ios');

		if (!$visitor->country_id) {
			$locationData = $this->fetchLocationApi($ipAddress);

			$visitor->country_id = Country::where('iso_code', $locationData['iso_code'])->first()->id;

			$visitor->mobile_connection = ($locationData['carrier']) ? true : false;
			$visitor->carrier_from_data = ($locationData['carrier']) ? $locationData['carrier'] : null;
		}

		$visitor->save();

		return new VisitorResource($visitor);
	}

    public function set(VisitorRequest $request)
    {
    	$visitorRequestValidated = $request->validated();

    	$ipAddress = $request->server('GGP_REMOTE_ADDR');
    	$device = $visitorRequestValidated['device'];
    	$connection = $visitorRequestValidated['connection'];

    	if ($visitorRequestValidated['device'] == 'android') {
    		$response = $this->androidSet($connection, $ipAddress);
    	} else if ($visitorRequestValidated['device'] == 'ios') {
    		$response = $this->appleSet($ipAddress);
    	}

    	return response()->json($response, 200);
    }
}
