<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Contracts\OfferInterface;

class OfferController extends Controller
{
    protected $offerRepository;

    public function __construct(OfferInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function fetch(OfferRequest $request)
    {

        $offerRequestValidated = $request->validated();

        $uid = $offerRequestValidated['uid'];

        $response = $this->offerRepository->fetchOffers($uid);

        return response()->json($response, 200);
    }
}
