<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Visitor;
use App\Models\Country;

class NonmobileController extends Controller
{
	// TODO: ! CODE DUPLICATION !
	private function fetchLocationApi($ipAddress)
	{
		$apiResponse = Http::get(env('IP2LOCATION_BASE_URL').'?ip='.$ipAddress.'&key='.env('IP2LOCATION_API_KEY').'&package=WS1');

		$apiResponseData = $apiResponse->json();

		return [
			'iso_code' => $apiResponseData['country_code']
		];
	}

	private function recordNonMobileVisitor($ipAddress)
	{
		$visitor = Visitor::firstOrCreate(
			['ip_address' => $ipAddress, 'device' => 'non-mobile'],
			['uid' => (string) Str::uuid(), 'ip_address' => $ipAddress, 'device' => 'non-mobile']
		);

		if (!$visitor->country_id) {
			$locationData = $this->fetchLocationApi($ipAddress);

			$visitor->country_id = Country::where('iso_code', $locationData['iso_code'])->first()->id;
			$visitor->save();
		}
	}

    public function index(Request $request)
    {
    	$ipAddress = $request->server('GGP_REMOTE_ADDR');

    	$this->recordNonMobileVisitor($ipAddress);

    	return response('non mobile', 200);
    }
}
