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
	private function fetchSingleOfferByCountry()
	{
		return $this->visitor->offers()
			->whereIn('device', [$this->visitor->device, '*'])
			->whereIn('carrier', [$this->visitor->carrier_from_data, '*'])
			->where('type', 'main')
			->first();
	}

	private function fetchSingleOfferAnyCountry()
	{
		return Offer::where('country_id', null)
			->whereIn('device', [$this->visitor->device, '*'])
			->whereIn('carrier', [$this->visitor->carrier_from_data, '*'])
			->where('type', 'main')
			->first();
	}

	private function fetchSingleOffer()
	{
		if ($this->visitor->offers()->count() > 0) {
			$singleOffer = $this->fetchSingleOfferByCountry();
		} else {
			$singleOffer = $this->fetchSingleOfferAnyCountry();
		}

		return $singleOffer;
	}

	private function fetchMultipleOffersByCountry()
	{
		return $this->visitor->offers()
			->whereIn('device', [$this->visitor->device, '*'])
			->whereIn('carrier', [$this->visitor->carrier_from_data, '*'])
			->where('type', 'backup')
			->all();
	}

	private function fetchMultipleOffersAnyCountry()
	{
		return Offer::where('country_id', null)
			->whereIn('device', [$this->visitor->device, '*'])
			->whereIn('carrier', [$this->visitor->carrier_from_data, '*'])
			->where('type', 'backup')
			->get();
	}

	private function fetchMultipleOffers()
	{
		// TODO: ! CODE DUPLICATION !
		if ($this->visitor->offers()->count() > 0) {
			$multipleOffers = $this->fetchMultipleOffersByCountry();
		} else {
			$multipleOffers = $this->fetchMultipleOffersAnyCountry();
		}

		return $multipleOffers;
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