<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use App\Contracts\VisitorInterface;
use App\Http\Resources\VisitorResourceWrapper;
use App\Http\Resources\ConnectionResourceWrapper;
use App\Http\Resources\ConnectionCarrierListResource;
use App\Http\Resources\ConnectionResource;
use App\Models\Visitor;

class VisitorRepository implements VisitorInterface
{
	protected $visitor;

	public function set($ipAddress, $device, $connection):VisitorResourceWrapper
	{
		$this->visitor = Visitor::fetchOrCreate($ipAddress, $device, $connection);

		return new VisitorResourceWrapper($this->visitor);
	}

	public function connectionChanged($uid, $ipAddress):ConnectionResourceWrapper
	{
		$this->visitor = Visitor::findByUid($uid);

		if ($this->visitor->ip_address !== $ipAddress) {
			$this->visitor->updateConnectionAttributes($ipAddress);
		}

		return new ConnectionResourceWrapper($this->visitor);
	}

	public function updateCarrier($uid, $carrier):ConnectionResource
	{
		return new ConnectionResource(Visitor::updateCarrier($uid, $carrier));
	}
}