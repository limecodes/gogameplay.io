<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Http\Resources\MobileOfferResource;
use App\Models\Visitor;

class OfferController extends Controller
{
    public function fetch(OfferRequest $request) {

    	$offerRequestValidated = $request->validated();

    	$visitor = Visitor::where('uid', $offerRequestValidated['uid'])->first();

    	$offers = $visitor->country->offers;

    	$matchedOffer = $offers
            ->where('device', $visitor->device)
            ->where('carrier', $visitor->carrier_from_data)
            ->where('type', 'main')
            ->first();

    	if ($matchedOffer) {
    		$ret = [
                'success' => true,
                'offer' => new MobileOfferResource($matchedOffer)
            ];
    	} else {
    		$ret = [
    			'success' => false,
                'offer' => null
    		];
    	}

    	return response()->json($ret, 200);
    }
}
