<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarrierRequest;
use App\Contracts\VisitorInterface;

class CarrierController extends Controller
{
    protected $visitorRepository;

    public function __construct(VisitorInterface $visitorRepository)
    {
        $this->visitorRepository = $visitorRepository;
    }

    public function updateCarrier(CarrierRequest $request)
    {
        $carrierRequestValidated = $request->validated();

        $uid = $carrierRequestValidated['uid'];
        $carrier = $carrierRequestValidated['carrier'];

        $response = $this->visitorRepository->updateCarrier($uid, $carrier);

        return response()->json($response, 200);
    }
}
