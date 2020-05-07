<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Offer extends Model
{
    public static function fetchSingleOfferAnyCountry($device, $carrier)
    {
        return static::where('country_id', null)
            ->whereIn('device', [$device, Config::get('constants.devices.any')])
            ->whereIn('carrier', [$carrier, Config::get('constants.carriers.any')])
            ->where('type', 'main')
            ->first();
    }

    public static function fetchMultipleOffersAnyCountry($device, $carrier)
    {
        return static::where('country_id', null)
            ->whereIn('device', [$device, Config::get('constants.devices.any')])
            ->whereIn('carrier', [$carrier, Config::get('constants.carriers.any')])
            ->where('type', 'backup')
            ->get();
    }
}
