<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Visitor extends Model
{
    protected $fillable = ['uid', 'ip_address', 'device', 'mobile_connection'];

    protected $casts = [
        'mobile_connection' => 'boolean'
    ];

    public function country()
    {
    	return $this->belongsTo('App\Models\Country');
    }

    public static function findByUid($uid)
    {
    	return static::where('uid', $uid)->first();
    }

    public function offers()
    {
    	return $this->country->offers;
    }

    public function fetchSingleOfferByCountry()
    {
        return $this->offers()
            ->whereIn('device', [$this->device, Config::get('constants.devices.any')])
            ->whereIn('carrier', [$this->carrier_from_data, Config::get('constants.carriers.any')])
            ->where('type', 'main')
            ->first();
    }

    public function fetchMultipleOffersByCountry()
    {
        return $this->offers()
            ->whereIn('device', [$this->device, Config::get('constants.devices.any')])
            ->whereIn('carrier', [$this->carrier_from_data, Config::get('constants.carriers.any')])
            ->where('type', 'backup')
            ->all();
    }

    public function setOrUpdateBasicAttributes(int $countryId, ?string $carrier, bool $mobileConnection = null)
    {
        $this->country_id = $countryId;
        $this->carrier_from_data = $carrier;

        if ($mobileConnection !== null) {
            $this->mobile_connection = $mobileConnection;
        }

        $this->save();
    }

    public function setOrUpdateConnectionAttributes(bool $mobileConnection, ?string $carrier, ?string $ipAddress, int $countryId = null)
    {
        $this->mobile_connection = $mobileConnection;

        $this->carrier_from_data = $carrier;

        $this->ip_address = $ipAddress;

        if ($countryId !== null) {
            $this->country_id = $countryId;
        }

        $this->save();
    }

    public static function updateCarrier(string $uid, string $carrier):Visitor
    {
        $visitor = static::findByUid($uid);

        $visitor->carrier_from_data = $carrier;

        $visitor->save();

        return $visitor;
    }
}
