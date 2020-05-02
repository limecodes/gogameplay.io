<?php

namespace App\Contracts\VisitorInterface;

interface VisitorInterface
{
	public function recordOrFetch($ipAddress, $device);
}