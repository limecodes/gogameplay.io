<?php

namespace App\Repositories;

use App\Contracts\OfferInterface;
use App\Http\Resources\MobileOfferResource;
use App\Http\Resources\BackupOfferResource;
use App\Models\Visitor;
use App\Models\Offer;

class OfferRepository implements OfferInterface {

	protected $visitor;

	// Don't forget match country any device match carrier
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
		$backupOffers = $this->fetchMultipleOffers(); 

		if ($matchedOffer) {
			$ret = [
				'success' => true,
				'offer' => new MobileOfferResource($matchedOffer)
			];
		} else if ($backupOffers) {
			$ret = [
				'success' => false,
				'offer' => BackupOfferResource::collection($backupOffers)
			];
		} else {
			$ret = [
				'success' => false,
				'offer' => null
			];
		}

		return $ret;
	}

}