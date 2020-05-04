<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use App\Contracts\VisitorInterface;
use App\External\LocationApiInterface;
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

	private function setAndroid():void
	{
		if ( ($this->visitor->mobile_connection) && (!$this->visitor->country_id) ) {
			$locationData = $this->locationApi->getCountryAndDetectCarrier($this->visitor->ip_address);

			$countryId = $locationData['country_id'];
			$carrier = $locationData['carrier'];

			$this->visitor->setOrUpdateBasicAttributes($countryId, $carrier);
		}
	}

	private function setApple():void
	{
		if (!$this->visitor->country_id) {
			$locationData = $this->locationApi->getCountryAndDetectCarrier($this->visitor->ip_address);

			$countryId = $locationData['country_id'];
			$carrier = ($locationData['carrier']) ? $locationData['carrier'] : null;
			$mobileConnection = ($locationData['carrier']) ? true : false;

			$this->visitor->setOrUpdateBasicAttributes($countryId, $carrier, $mobileConnection);
		}
	}

	private function setNonMobile():void
	{
		if (!$this->visitor->country_id) {
			$locationData = $this->locationApi->getCountryOnly($this->visitor->ip_address);

			$countryId = $locationData['country_id'];

			$this->visitor->setOrUpdateBasicAttributes($countryId, null);
		}
	}

	private function connectionChangedAndroid($ipAddress):void
	{
		$mobileConnection = true;

		if ( (!$this->visitor->country_id) && (!$this->visitor->carrier_from_data) ) {
			$locationData = $this->locationApi->getCountryAndDetectCarrier($this->visitor->ip_address);

			$countryId = $locationData['country_id'];
			$carrier = ($locationData['carrier']) ? $locationData['carrier'] : null;
		}

		$this->visitor->setOrUpdateConnectionAttributes($mobileConnection, $carrier, $ipAddress, $countryId);
	}

	private function connectionChangedApple($ipAddress):void
	{
		$locationData = ($this->visitor->ip_address !== $ipAddress)
			? $this->locationApi->getCountryAndDetectCarrier($ipAddress)
			: null;

		if ($locationData !== null) {
			$mobileConnection = ($locationData['carrier']) ? true : false;
			$carrier = ($locationData['carrier']) ? $locationData['carrier'] : null;

			$this->visitor->setOrUpdateConnectionAttributes($mobileConnection, $carrier, $ipAddress);
		}
	}

	public function set($ipAddress, $device, $connection):VisitorResourceWrapper
	{
		$this->visitor = Visitor::firstOrCreate(
			['ip_address' => $ipAddress, 'device' => $device],
			['uid' => (string) Str::uuid(), 'ip_address' => $ipAddress, 'device' => $device, 'mobile_connection' => $connection]
		);

		if ($this->visitor->device == Config::get('constants.devices.android')) {
			$this->setAndroid();
		} else if ($this->visitor->device == Config::get('constants.devices.ios')) {
			$this->setApple();
		} else if ($this->visitor->device == Config::get('constants.devices.non_mobile')) {
			$this->setNonMobile();
		}

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