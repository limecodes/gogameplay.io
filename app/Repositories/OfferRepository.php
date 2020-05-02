<?php

namespace App\Repositories;

use App\Contracts\OfferInterface;
use App\Http\Resources\MobileOfferResource;
use App\Models\Visitor;

class OfferRepository implements OfferInterface {

	public function fetchOffers($uid)
	{
		$visitor = Visitor::findByUid($uid);

		$matchedOfferPreferred = $visitor->offers()
			->where('device', $visitor->device)
			->where('carrier', $visitor->carrier_from_data)
			->where('type', 'main')
			->first();

		// if ($matchedOfferAttempt->count() > 0) {
		// 	$matchedOffer = $matchedOfferAttempt->first();
		// }

		// $matchedOfferAttempt = $visitor->offers()
		// 	->where('device', $visitor->device)
		// 	->where('carrier', '*')
		// 	->where('type', 'main');

		// if ($matchedOfferAttempt->count() > 0) {
		// 	$matchedOffer = $matchedOfferAttempt->first();
		// }

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

		return $ret;
	}

}