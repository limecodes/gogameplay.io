<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = ['uid', 'ip_address'];

    public function country()
    {
    	return $this->belongsTo('App\Models\Country');
    }
}
