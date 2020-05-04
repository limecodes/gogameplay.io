<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
            ->whereIn('device', [$this->device, '*'])
            ->whereIn('carrier', [$this->carrier_from_data, '*'])
            ->where('type', 'main')
            ->first();
    }

    public function fetchMultipleOffersByCountry()
    {
        return $this->offers()
            ->whereIn('device', [$this->device, '*'])
            ->whereIn('carrier', [$this->carrier_from_data, '*'])
            ->where('type', 'backup')
            ->all();
    }
}
