<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Contracts\VisitorInterface;
use App\External\LocationApiInterface;
use App\Http\Resources\VisitorResourceWrapper;
use App\Http\Resources\ConnectionResourceWrapper;
use App\Http\Resources\VisitorCarrierListResource;
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

			$this->visitor->country_id = $locationData['country_id'];
			$this->visitor->carrier_from_data = $locationData['carrier'];

			$this->visitor->save();
		}
	}

	private function setApple():void
	{
		if (!$this->visitor->country_id) {
			$locationData = $this->locationApi->getCountryAndDetectCarrier($this->visitor->ip_address);

			$this->visitor->country_id = $locationData['country_id'];

			$this->visitor->mobile_connection = ($locationData['carrier']) ? true : false;

			$this->visitor->carrier_from_data = ($locationData['carrier']) ? $locationData['carrier'] : null;

			$this->visitor->save();
		}
	}

	private function setNonMobile():void
	{
		if (!$this->visitor->country_id) {
			$locationData = $this->locationApi->getCountryOnly($this->visitor->ip_address);

			$this->visitor->country_id = $locationData['country_id'];

			$this->visitor->save();
		}
	}

	private function connectionChangedAndroid($ipAddress):void
	{
		$this->visitor->ip_address = $ipAddress;
		$this->visitor->mobile_connection = true;

		if ( (!$this->visitor->country_id) && (!$this->visitor->carrier_from_data) ) {
			$locationData = $this->locationApi->getCountryAndDetectCarrier($this->visitor->ip_address);

			$this->visitor->country_id = $locationData['country_id'];
			$this->visitor->carrier_from_data = ($locationData['carrier']) ? $locationData['carrier'] : null;
		}

		$this->visitor->save();
	}

	private function connectionChangedApple($ipAddress):void
	{
		if ($this->visitor->ip_address !== $ipAddress) {
			$this->visitor->ip_address = $ipAddress;

			$locationData = $this->locationApi->getCountryAndDetectCarrier($this->visitor->ip_address);

			$this->visitor->mobile_connection = ($locationData['carrier']) ? true : false;
			$this->visitor->carrier_from_data = ($locationData['carrier']) ? $locationData['carrier'] : null;
		}

		$this->visitor->save();
	}

	public function set($ipAddress, $device, $connection):VisitorResourceWrapper
	{
		$this->visitor = Visitor::firstOrCreate(
			['ip_address' => $ipAddress, 'device' => $device],
			['uid' => (string) Str::uuid(), 'ip_address' => $ipAddress, 'device' => $device, 'mobile_connection' => $connection]
		);

		if ($this->visitor->device == 'android') {
			$this->setAndroid();
		} else if ($this->visitor->device == 'ios') {
			$this->setApple();
		} else if ($this->visitor->device == 'non-mobile') {
			$this->setNonMobile();
		}

		return new VisitorResourceWrapper($this->visitor);
	}

	public function connectionChanged($uid, $ipAddress):ConnectionResourceWrapper
	{
		$this->visitor = Visitor::findByUid($uid);

		if ($this->visitor->device == 'android') {
			$this->connectionChangedAndroid($ipAddress);
		} else if ($this->visitor->device == 'ios') {
			$this->connectionChangedApple($ipAddress);
		}

		return new ConnectionResourceWrapper($this->visitor);
	}

	public function updateCarrier($uid, $carrier):ConnectionResource
	{
		$this->visitor = Visitor::findByUid($uid);

		$this->visitor->carrier_from_data = $carrier;

		$this->visitor->save();

		return new ConnectionResource($this->visitor);
	}
}