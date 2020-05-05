<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use App\Facades\DeviceHelper;
use App\Facades\LocationApi;

class Visitor extends Model
{
    protected $fillable = ['uid', 'ip_address', 'device', 'mobile_connection'];

    protected $casts = [
        'mobile_connection' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->uid = (string) Str::uuid();

            if ($model->device == Config::get('constants.devices.android')) {
                $model = DeviceHelper::getDataAndroid($model);
            } else if ($model->device == Config::get('constants.devices.ios')) {
                $model = DeviceHelper::getDataApple($model);
            } else if ($model->device == Config::get('constants.devices.non_mobile')) {
                $model = DeviceHelper::getDataNonMobile($model);
            }
        });

        static::updating(function($model) {
            $model = DeviceHelper::updatedData($model);
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

    public static function fetchOrCreate(string $ipAddress, string $device, bool $connection):Visitor
    {
        return Visitor::firstOrCreate(
            ['ip_address' => $ipAddress, 'device' => $device],
            ['ip_address' => $ipAddress, 'device' => $device, 'mobile_connection' => $connection]
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

    public function updateConnectionAttributes(?string $ipAddress)
    {
        $this->ip_address = $ipAddress;

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
