<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CarrierRequest;
use App\Http\Resources\ConnectionResource;
use App\Models\Visitor;

class CarrierController extends Controller
{
    public function updateCarrier(CarrierRequest $request)
    {
    	$carrierRequestValidated = $request->validated();

    	$visitor = Visitor::where('uid', $carrierRequestValidated['uid'])->first();

    	$visitor->carrier_from_data = $carrierRequestValidated['carrier'];

    	$visitor->save();

    	return response()->json(new ConnectionResource($visitor), 200);
    }
}
