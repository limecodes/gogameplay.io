<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Visitor;
use App\Models\Country;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function index($name, Request $request)
    {
    	$validator = Validator::make(['name' => $name], [
    		'name' => 'required|string|alpha|max:50'
    	]);

    	if ($validator->fails()) {
    		return response($validator->errors(), 422);
    	}

        $ipAddress = $request->server('GGP_REMOTE_ADDR');

        $visitor = Visitor::firstOrCreate(['ip_address' => $ipAddress], ['uid' => (string) Str::uuid(), 'ip_address' => $ipAddress]);

        $apiKey = env('IP2LOCATION_API_KEY');

        $userAgent = $request->server->get('HTTP_USER_AGENT');
        $iPhone = stripos($userAgent, 'iPhone');
        $android = stripos($userAgent, 'Android');

        if ($iPhone) {
            $visitor->device = 'ios';
        } else if ($android) {
            $visitor->device = 'android';
        } else {
            $visitor->device = 'non-mobile';
        }

        if (!$visitor->country_id) {
            $apiResponse = Http::get('https://api.ip2location.com/v2/?ip='.$ipAddress.'&key='.$apiKey.'&package=WS24');

            $apiVisitorData = $apiResponse->json();

            $countryId = Country::where('iso_code', strtolower($apiVisitorData['country_code']))->first()->id;

            $visitor->country_id = $countryId;

            if ( ($apiVisitorData['mobile_brand'] !== '-') && ($apiVisitorData['usage_type'] == 'MOB') ) {
                $visitor->mobile_connection = true;
                $visitor->carrier_from_data = $apiVisitorData['mobile_brand'];
            } else {
                $visitor->mobile_connection = false;
            }

            $visitor->save();
        }

    	// TODO: This should actually be done through a service //
    	$game = Game::where('name', $name);

    	if ($game->count() > 0) {
    		// return view('welcome');
            return response("The name of the game is $name and your ip is $ipAddress", 200);
    	} else {
    		return response('game does not exist', 404);
    	}
    }
}
