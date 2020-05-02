<?php

namespace App\Contracts;

use App\Http\Resources\ConnectionResource;

interface VisitorInterface
{
	public function set($ipAddress, $device, $connection);
	public function connectionChanged($uid, $ipAddress);
	public function updateCarrier($uid, $carrier):ConnectionResource;
}