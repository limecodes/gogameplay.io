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
use App\Http\Requests\CarrierRequest;
use App\Http\Requests\CarrierListRequest;
use App\Http\Resources\ConnectionResource;
use App\Http\Resources\CarrierResource;

class GameController extends Controller
{
    private function recordVisitorNonMobile($ipAddress)
    {
        $visitor = Visitor::firstOrCreate(
            ['ip_address' => $ipAddress],
            ['uid' => (string) Str::uuid(), 'ip_address' => $ipAddress, 'device' => 'non-mobile', 'mobile_connection' => false]
        );

        $apiResponse = Http::get(env('IP2LOCATION_BASE_URL').'?ip='.$ipAddress.'&key='.env('IP2LOCATION_API_KEY').'&package=WS1');

        $apiVisitorData = $apiResponse->json();

        $countryId = Country::where('iso_code', strtolower($apiVisitorData['country_code']))->first()->id;

        $visitor->country_id = $countryId;

        $visitor->save();
    }

    private function recordVisitor($ipAddress, $device, $connection)
    {
        $visitor = Visitor::firstOrCreate(
            ['ip_address' => $ipAddress, 'device' => $device],
            ['uid' => (string) Str::uuid(), 'ip_address' => $ipAddress, 'device' => $device, 'mobile_connection' => $connection]
        );

        if (!$visitor->country_id) {

            // TODO: Should only lookup user if country isn't filled and connection is false
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

        }

        return $visitor;
    }

    public function nonmobile(Request $request)
    {
        $ipAddress = $request->server('GGP_REMOTE_ADDR');

        $visitor = $this->recordVisitorNonMobile($ipAddress);

        return response('non mobile', 200);
    }

    public function index($name, Request $request)
    {
    	$validator = Validator::make(['name' => $name], [
    		'name' => 'required|string|alpha|max:50'
    	]);

    	if ($validator->fails()) {
    		return response($validator->errors(), 422);
    	}

    	$game = Game::where('name', $name);

    	if ($game->count() > 0) {
            $device = $request->headers->get('device');

            $gameObject = $game->first();
            $gameTitle = $gameObject->title;
            $gameImage = $gameObject->image;
            $gamePrice = $gameObject->price;

    		return view('game', ['title' => $gameTitle, 'image' => $gameImage, 'price' => $gamePrice, 'device' => $device]);
    	} else {
    		return response('game does not exist', 404);
    	}
    }

    public function connection(ConnectionRequest $request)
    {
        $connectionRequestValidated = $request->validated();

        $ipAddress = $request->server('GGP_REMOTE_ADDR');

        $visitor = Visitor::where('uid', $connectionRequestValidated['uid'])->first();

        if ($visitor->ip_address !== $ipAddress) {
            $apiResponse = Http::get(env('IP2LOCATION_BASE_URL').'?ip='.$ipAddress.'&key='.env('IP2LOCATION_API_KEY').'&package=WS19');

            $apiVisitorData = $apiResponse->json();

            if ( ($visitor->mobile_connection == false) && ($apiVisitorData['mobile_brand'] !== '-') ) {
                $visitor->ip_address = $request->server('GGP_REMOTE_ADDR');
                $visitor->mobile_connection = true;
                $visitor->carrier_from_data = $apiVisitorData['mobile_brand'];
            } else if ($apiVisitorData['mobile_brand'] == '-') {
                $visitor->ip_address = $request->server('GGP_REMOTE_ADDR');
                $visitor->carrier_from_data = 'unknown';
            }

            $visitor->save();
        }

        if ( ($visitor->carrier_from_data == 'unknown') || (!$visitor->carrier_from_data) ){
            $response = [
                'visitor' => new ConnectionResource($visitor),
                'carriers_by_country' => CarrierResource::collection($visitor->country->mobileNetwork)
            ];
        } else {
            $response = new ConnectionResource($visitor);
        }

        return response()->json($response, 200);
    }

    public function carrier(CarrierRequest $request)
    {
        $carrierRequestValidated = $request->validated();

        $visitor = Visitor::where('uid', $carrierRequestValidated['uid'])->first();

        // TODO: Should I really do carrier_from_data or create another column for user entered carrier
        $visitor->carrier_from_data = $carrierRequestValidated['carrier'];

        $visitor->save();

        return response()->json(new ConnectionResource($visitor), 200);
    }

    public function carrierlist(CarrierListRequest $request)
    {
        $carrierListRequestValidated = $request->validated();

        $visitor = Visitor::where('uid', $carrierListRequestValidated['uid'])->first();

        return response()->json(CarrierResource::collection($visitor->country->mobileNetwork));
    }
}
