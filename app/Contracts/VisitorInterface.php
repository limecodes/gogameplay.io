<?php

namespace App\Contracts;

interface VisitorInterface
{
	public function set($ipAddress, $device, $connection);
}