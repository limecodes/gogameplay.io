<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DeviceHelper extends Facade {

	protected static function getFacadeAccessor()
	{
		return 'DeviceHelper';
	}

}
