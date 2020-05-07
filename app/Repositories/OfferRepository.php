<?php

namespace App\Repositories;

use App\Contracts\OfferInterface;
use App\Http\Resources\MobileOfferResource;
use App\Http\Resources\BackupOfferResource;
use App\Models\Visitor;
use App\Models\Offer;

class OfferRepository implements OfferInterface
{

    protected $visitor;

    private function fetchSingleOffer()
    {
        $singleOfferByCountry = $this->visitor->fetchSingleOfferByCountry();

        return ($singleOfferByCountry)
            ? $singleOfferByCountry
            : Offer::fetchSingleOfferAnyCountry($this->visitor->device, $this->visitor->carrier_from_data);
    }

    private function fetchMultipleOffers()
    {
        $multipleOffersByCountry = $this->visitor->fetchMultipleOffersByCountry();

        return ($multipleOffersByCountry)
            ? $multipleOffersByCountry
            : Offer::fetchMultipleOffersAnyCountry($this->visitor->device, $this->visitor->carrier_from_data);
    }

    public function fetchOffers($uid)
    {
        $this->visitor = Visitor::findByUid($uid);

        $matchedOffer = $this->fetchSingleOffer();

        if ($matchedOffer) {
            $ret = [
                'success' => true,
                'offer' => new MobileOfferResource($matchedOffer)
            ];
        } else {
            $backupOffers = $this->fetchMultipleOffers();

            $ret = [
                'success' => false,
                'offer' => ($backupOffers) ? BackupOfferResource::collection($backupOffers) : null
            ];
        }

        return $ret;
    }
}
