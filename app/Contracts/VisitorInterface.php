<?php

namespace App\Contracts;

use App\Http\Resources\VisitorResource;

interface VisitorInterface
{
	public function set($ipAddress, $device, $connection):VisitorResource;
}