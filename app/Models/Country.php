<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	public $timestamps = false;
	
    public function mobileNetwork()
    {
    	return $this->hasMany('App\Models\MobileNetwork');
    }

    public function offers()
    {
    	return $this->hasMany('App\Models\Offer');
    }

    public static function getCountryIdByIsoCode($isoCode)
    {
        return static::where('iso_code', $isoCode)->first()->id;
    }
}
