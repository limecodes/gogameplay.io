<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    public static function fetchSingleOfferAnyCountry($device, $carrier)
	{
		return static::where('country_id', null)
			->whereIn('device', [$device, '*'])
			->whereIn('carrier', [$carrier, '*'])
			->where('type', 'main')
			->first();
	}

	public static function fetchMultipleOffersAnyCountry($device, $carrier)
	{
		return static::where('country_id', null)
			->whereIn('device', [$device, '*'])
			->whereIn('carrier', [$carrier, '*'])
			->where('type', 'backup')
			->get();
	}
}
