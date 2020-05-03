<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Contracts\OfferInterface;
use App\Http\Resources\MobileOfferResource;

class OfferController extends Controller
{
    protected $offerRepository;

    public function __construct(OfferInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function fetch(OfferRequest $request) {

    	$offerRequestValidated = $request->validated();

        $uid = $offerRequestValidated['uid'];

        return response()->json($this->offerRepository->fetchOffers($uid), 200);
    }
}
