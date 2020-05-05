<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Game extends Model
{
    protected $fillable = ['name'];

    public static function boot()
    {
    	parent::boot();

    	// This could be useful in other things :-)
    	static::saving(function($model) {
    		$model->slug = Str::slug($model->name);
    	});
    }

    public function getRouteKeyName()
    {
    	return 'slug';
    }
}
