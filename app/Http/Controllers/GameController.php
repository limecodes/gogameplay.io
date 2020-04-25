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

        // Visitor is already created when the page is rendered, so I need to pass the uuid to the React application
        // On Android, if the user changes their IP, then I need to update the visitor record with the UUID
        // The uuid is persisted in the local storage.
        // I can also have a flash for Android if the user is on wifi
        // For iPhone, I don't have access to the connection type so I can only tell by IP
        // I need to make sure that I don't call the IP2Location API more than I need to
        // User then chooses their carrier and proceeds to offer selection

        // TODO: Get the user's geo location by their IP address
        // On Android (network.connection)
        //  -> Check if the user if on wifi
        //      -> If the user is on wifi, ask the user to switch to cellular connection
        //      -> Onchange of network connection, update the IP and lookup the carrier
        // On iPhone
        //  -> Check the user's IP and check if mobile carrier values are there
        //  -> If they are not present, then ask the user to switch to cellular network
        //  -> Check if the IP address changed and do another lookup
        //  -> Have them select their carrier

        // UUID will persist, but IP address may change

        // In order to render the first page, I only need the geolocation to render the list
        // of carriers, I have two options:
        // 1. Trust the user to select their own carrier
        // 2. Do additional checks to ensure that the user is on a cellular connection

        // NEXT TODO:
        // 1. Echo the user IP address
        // 2. Setup React
        // 3. Echo the user's geolocation

    	// To make the controller return 422
    	// Later, will show a page with an error
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
