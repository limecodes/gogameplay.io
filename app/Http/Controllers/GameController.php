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
use App\Http\Requests\ConnectionRequest;
use App\Http\Resources\ConnectionResource;

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

        if ( ($visitor->mobile_connection == false) && ($apiVisitorData['mobile_brand'] !== '-') ) {
            $visitor->mobile_connection = true;
            $visitor->carrier_from_data = $apiVisitorData['mobile_brand'];
        } else if ($apiVisitorData['mobile_brand'] !== '-') {
            $visitor->carrier_from_data = $apiVisitorData['mobile_brand'];
        }

        $visitor->save();

        return $visitor;
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
            $visitor = $this->recordVisitor($ipAddress, $device, $connection);

            $gameName = $game->first()->name;

            $uid = $visitor->uid;
            $device = $visitor->device;
            $connection = boolval($visitor->mobile_connection);
            $carrier = $visitor->carrier_from_data;

    		return view('game', ['name' => $gameName, 'uid' => $uid, 'device' => $device, 'connection' => $connection, 'carrier' => $carrier]);
            // return response("The name of the game is $name and your ip is $ipAddress", 200);
    	} else {
    		return response('game does not exist', 404);
    	}
    }

    public function connection(ConnectionRequest $request)
    {
        // BUG: Duplicate entry when trying to update if the user has been on the site before
        //      It's not grabbing the uid by previously used ip address
        //      The bug is in firstOrCreate, it's not grabbing a previous user by ip address
        //      This will eat up my API usage, I need to fix this to grab a previous user
        //      Without using firstOrCreate
        //      NO WAIT!!
        //      When a user comes in with wifi, then changes to cellular, it's not grabbing the user
        //      because it's not detecting their cellular IP, since I'm updating it.
        //      A fix would be to add another field or another record, instead of updating it.
        //      OR: I can add an ip_address-to-user_id table
        $connectionRequestValidated = $request->validated();

        $ipAddress = $request->server('GGP_REMOTE_ADDR');

        $visitor = Visitor::where('uid', $connectionRequestValidated['uid'])->first();

        $apiResponse = Http::get(env('IP2LOCATION_BASE_URL').'?ip='.$ipAddress.'&key='.env('IP2LOCATION_API_KEY').'&package=WS19');

        $apiVisitorData = $apiResponse->json();

        if ( ($visitor->mobile_connection == false) && ($apiVisitorData['mobile_brand'] !== '-') ) {
            $visitor->ip_address = $request->server('GGP_REMOTE_ADDR');
            $visitor->mobile_connection = true;
            $visitor->carrier_from_data = $apiVisitorData['mobile_brand'];

            $visitor->save();
        }

        return response()->json(new ConnectionResource($visitor), 200);
    }
}
