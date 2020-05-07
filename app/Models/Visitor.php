<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use App\Facades\LocationApi;

class Visitor extends Model
{
    protected $fillable = ['ip_address', 'device', 'mobile_connection', 'country_id', 'carrier_from_data'];

    protected $casts = [
        'mobile_connection' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uid = (string) Str::uuid();

            if ($model->device !== Config::get('constants.devices.non_mobile')) {
                $model = static::getMobileData($model);
            } else {
                $model->country_id = LocationApi::getCountryOnly($model->ip_address)['country_id'];
            }
        });

        static::updating(function ($model) {
            $model = static::updateMobileData($model);
        });
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function offers()
    {
        return $this->country->offers;
    }

    public static function findByUid($uid)
    {
        return static::where('uid', $uid)->first();
    }

    public static function fetchOrCreate(string $ipAddress, string $device, ?bool $connection):Visitor
    {
        return Visitor::firstOrCreate(
            ['ip_address' => $ipAddress, 'device' => $device],
            ['ip_address' => $ipAddress, 'device' => $device, 'mobile_connection' => $connection]
        );
    }

    private static function getMobileData(Visitor $visitor):Visitor
    {
        if (($visitor->mobile_connection) && (!$visitor->country_id)) {
            $locationData = LocationApi::getCountryAndDetectCarrier($visitor->ip_address);

            $visitor->fill([
                'country_id' => $locationData['country_id'],
                'carrier_from_data' => $locationData['carrier']
            ]);
        } elseif (($visitor->mobile_connection === null) && (!$visitor->country_id)) {
            $locationData = LocationApi::getCountryAndDetectCarrier($visitor->ip_address);

            $visitor->fill([
                'country_id' => $locationData['country_id'],
                'carrier_from_data' => $locationData['carrier'],
                'mobile_connection' => $locationData['connection']
            ]);
        }

        return $visitor;
    }

    private static function updateMobileData(Visitor $visitor):Visitor
    {
        if ((!$visitor->country_id) || (!$visitor->carrier_from_data)) {
            $locationData = LocationApi::getCountryAndDetectCarrier($visitor->ip_address);

            $visitor->fill([
                'country_id' => $locationData['country_id'],
                'carrier_from_data' => $locationData['carrier'],
                'mobile_connection' => $locationData['connection']
            ]);
        }

        return $visitor;
    }

    public static function updateCarrier(string $uid, string $carrier):Visitor
    {
        $visitor = static::findByUid($uid);

        $visitor->update(['carrier_from_data' => $carrier]);

        return $visitor;
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
}
