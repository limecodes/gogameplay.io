<?php

namespace App\Contracts;

use App\Http\Resources\VisitorResourceWrapper;
use App\Http\Resources\ConnectionResource;
use App\Http\Resources\ConnectionResourceWrapper;

interface VisitorInterface
{
    public function set($ipAddress, $device, $connection):VisitorResourceWrapper;
    public function connectionChanged($uid, $ipAddress):ConnectionResourceWrapper;
    public function updateCarrier($uid, $carrier):ConnectionResource;
}
