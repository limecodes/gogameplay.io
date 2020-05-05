<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use App\Contracts\VisitorInterface;
use App\Contracts\LocationApiInterface;
use App\Http\Resources\VisitorResourceWrapper;
use App\Http\Resources\ConnectionResourceWrapper;
use App\Http\Resources\ConnectionCarrierListResource;
use App\Http\Resources\ConnectionResource;
use App\Models\Visitor;
use App\Models\Country;

class VisitorRepository implements VisitorInterface
{
	protected $visitor;
	protected $locationApi;

	public function __construct(LocationApiInterface $locationApi)
	{
		$this->locationApi = $locationApi;
	}

	private function connectionChangedAndroid($ipAddress):void
	{
		$mobileConnection = true;

		$this->visitor->updateConnectionAttributes($ipAddress, $mobileConnection);
	}

	private function connectionChangedApple($ipAddress):void
	{
		if ($this->visitor->ip_address !== $ipAddress) {
			$this->visitor->updateConnectionAttributes($ipAddress);
		}
	}

	public function set($ipAddress, $device, $connection):VisitorResourceWrapper
	{
		$this->visitor = Visitor::fetchOrCreate($ipAddress, $device, $connection);

		return new VisitorResourceWrapper($this->visitor);
	}

	public function connectionChanged($uid, $ipAddress):ConnectionResourceWrapper
	{
		$this->visitor = Visitor::findByUid($uid);

		if ($this->visitor->device == Config::get('constants.devices.android')) {
			$this->connectionChangedAndroid($ipAddress);
		} else if ($this->visitor->device == Config::get('constants.devices.ios')) {
			$this->connectionChangedApple($ipAddress);
		}

		return new ConnectionResourceWrapper($this->visitor);
	}

	public function updateCarrier($uid, $carrier):ConnectionResource
	{
		return new ConnectionResource(Visitor::updateCarrier($uid, $carrier));
	}
}