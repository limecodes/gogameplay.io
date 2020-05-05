<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use App\Facades\LocationApi;

class Visitor extends Model
{
    protected $fillable = ['uid', 'ip_address', 'device', 'mobile_connection'];

    protected $casts = [
        'mobile_connection' => 'boolean'
    ];

    private static function location(LocationApiInterface $locationApi)
    {
        return $locationApi;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->uid = (string) Str::uuid();

            if ( ($model->device == Config::get('constants.devices.android')) && ($model->mobile_connection) && (!$model->country_id) ) {
                $locationData = LocationApi::getCountryAndDetectCarrier($model->ip_address);

                $model->country_id = $locationData['country_id'];
                $model->carrier_from_data = $locationData['carrier'];
            } else if ( ($model->device == Config::get('constants.devices.ios')) && (!$model->country_id) ) {
                $locationData = LocationApi::getCountryAndDetectCarrier($model->ip_address);

                $model->country_id = $locationData['country_id'];
                $model->carrier_from_data = $locationData['carrier'];
                $model->mobile_connection = $locationData['connection'];
            }
        });

        static::updating(function($model) {
            var_dump('expression');
        });
    }

    public function country()
    {
    	return $this->belongsTo('App\Models\Country');
    }

    public static function findByUid($uid)
    {
    	return static::where('uid', $uid)->first();
    }

    public static function fetchOrNew(string $ipAddress, string $device, bool $connection):Visitor
    {
        return Visitor::firstOrNew(
            ['ip_address' => $ipAddress, 'device' => $device],
            ['uid' => (string) Str::uuid(), 'ip_address' => $ipAddress, 'device' => $device, 'mobile_connection' => $connection]
        );
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

    public function updateConnectionAttributes(bool $mobileConnection, ?string $carrier, ?string $ipAddress, int $countryId = null)
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
