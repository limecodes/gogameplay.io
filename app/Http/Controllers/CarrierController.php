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
    }
}
