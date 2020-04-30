<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OfferRequest;
use App\Http\Resources\MobileOfferResource;
use App\Models\Visitor;

class OfferController extends Controller
{
    public function fetch(OfferRequest $request) {

    	$offerRequestValidated = $request->validated();

    	$visitor = Visitor::where('uid', $offerRequestValidated['uid'])->first();

    	$offers = $visitor->country->offers;

    	$matchedOffer = $offers->where('carrier', 'Vodafone')->where('type', 'main')->first();

    	return response()->json(new MobileOfferResource($matchedOffer), 200);
    }
}
