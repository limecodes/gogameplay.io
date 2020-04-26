<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Visitor;
use App\Models\Country;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Http\Requests\GameRequest;

class GameController extends Controller
{
    private function recordVisitor($ipAddress, $device, $connection)
    {
        $visitor = Visitor::firstOrCreate(
            ['ip_address' => $ipAddress],
            ['uid' => (string) Str::uuid(), 'ip_address' => $ipAddress, 'device' => $device, 'mobile_connection' => $connection]
        );

        $apiResponse = Http::get(env('IP2LOCATION_BASE_URL').'?ip='.$ipAddress.'&key='.env('IP2LOCATION_API_KEY').'&package=WS19');

        $apiVisitorData = $apiResponse->json();

        $countryId = Country::where('iso_code', strtolower($apiVisitorData['country_code']))->first()->id;

        $visitor->country_id = $countryId;

        if ( ($visitor->mobile_connection === false) && ($apiVisitorData['mobile_brand'] !== '-') ) {
            $visitor->mobile_connection = true;
            $visitor->carrier_from_data = $apiVisitorData['mobile_brand'];
        }

        $visitor->save();
    }

    public function index($name, GameRequest $request)
    {
    	$validator = Validator::make(['name' => $name], [
    		'name' => 'required|string|alpha|max:50'
    	]);

    	if ($validator->fails()) {
    		return response($validator->errors(), 422);
    	}

        $gameRequestValidated = $request->validated();

    	$game = Game::where('name', $name);

    	if ($game->count() > 0) {
            $ipAddress = $request->server('GGP_REMOTE_ADDR');
            $device = $request->headers->get('device');
            $connection = boolval($gameRequestValidated['connection']);
            $this->recordVisitor($ipAddress, $device, $connection);

    		// return view('welcome');
            return response("The name of the game is $name and your ip is $ipAddress", 200);
    	} else {
    		return response('game does not exist', 404);
    	}
    }
}
