<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public function mobileNetwork()
    {
    	return $this->hasMany('App\Models\MobileNetwork');
    }
}
