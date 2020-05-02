<?php

namespace App\Contracts;

interface VisitorInterface
{
	public function set($ipAddress, $device, $connection);
	public function connectionChanged($uid, $device, $ipAddress);
	public function updateCarrier($uid, $carrier);
}