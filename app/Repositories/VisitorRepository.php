<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Contracts\VisitorInterface;
use App\External\LocationApi;
use App\Http\Resources\VisitorResource;
use App\Http\Resources\ConnectionResource;
use App\Http\Resources\VisitorCarrierListResource;
use App\Http\Resources\ConnectionCarrierListResource;
use App\Models\Visitor;
use App\Models\Country;

class VisitorRepository implements VisitorInterface
{
	protected $visitor;
	protected $locationApi;

	public function __construct(LocationApi $locationApi)
	{
		$this->locationApi = $locationApi;
	}

	private function setAndroid()
	{
		if ( ($this->visitor->mobile_connection) && (!$this->visitor->country_id) ) {
			$locationData = $this->locationApi->getCountryAndDetectCarrier($this->visitor->ip_address);

			$this->visitor->country_id = Country::getCountryId($locationData['iso_code']);
			$this->visitor->carrier_from_data = $locationData['carrier'];

			$this->visitor->save();
		}

		return ( ($this->visitor->mobile_connection) && ($this->visitor->carrier_from_data == null) )
			? new VisitorCarrierListResource($this->visitor) : new VisitorResource($this->visitor);
	}

	private function setApple()
	{
		if (!$this->visitor->country_id) {
			$locationData = $this->locationApi->getCountryAndDetectCarrier($this->visitor->ip_address);

			$this->visitor->country_id = Country::getCountryId($locationData['iso_code']);

			$this->visitor->mobile_connection = ($locationData['carrier']) ? true : false;

			$this->visitor->carrier_from_data = ($locationData['carrier']) ? $locationData['carrier'] : null;

			$this->visitor->save();
		}

		return new VisitorResource($this->visitor);
	}

	public function set($ipAddress, $device, $connection)
	{
		$this->visitor = Visitor::firstOrCreate(
			['ip_address' => $ipAddress, 'device' => $device],
			['uid' => (string) Str::uuid(), 'ip_address' => $ipAddress, 'device' => $device, 'mobile_connection' => $connection]
		);

		if ($this->visitor->device == 'android') {
			$ret = $this->setAndroid();
		} else if ($this->visitor->device == 'ios') {
			$ret = $this->setApple();
		}

		return $ret;
	}

	private function connectionChangedAndroid($ipAddress)
	{
		$this->visitor->ip_address = $ipAddress;
		$this->visitor->mobile_connection = true;

		if ( (!$this->visitor->country_id) && (!$this->visitor->carrier_from_data) ) {
			$locationData = $this->locationApi->getCountryAndDetectCarrier($this->visitor->ip_address);

			$this->visitor->country_id = Country::getCountryId($locationData['iso_code']);
			$this->visitor->carrier_from_data = ($locationData['carrier']) ? $locationData['carrier'] : null;
		}

		$this->visitor->save();

		return ( ($this->visitor->mobile_connection == true) && ($this->visitor->carrier_from_data == null) )
			? new ConnectionCarrierListResource($this->visitor) : new ConnectionResource($this->visitor);
	}

	private function connectionChangedApple($ipAddress)
	{
		if ($this->visitor->ip_address !== $ipAddress) {
			$this->visitor->ip_address = $ipAddress;

			$locationData = $this->locationApi->getCountryAndDetectCarrier($this->visitor->ip_address);

			$this->visitor->mobile_connection = ($locationData['carrier']) ? true : false;
			$this->visitor->carrier_from_data = ($locationData['carrier']) ? $locationData['carrier'] : null;
		}

		$this->visitor->save();

		return (!$this->visitor->mobile_connection)
			? new ConnectionCarrierListResource($this->visitor) : new ConnectionResource($this->visitor);
	}

	public function connectionChanged($uid, $ipAddress)
	{
		$this->visitor = Visitor::findByUid($uid);

		if ($this->visitor->device == 'android') {
			$ret = $this->connectionChangedAndroid($ipAddress);
		} else if ($this->visitor->device == 'ios') {
			$ret = $this->connectionChangedApple($ipAddress);
		}

		return $ret;
	}

	public function updateCarrier($uid, $carrier):ConnectionResource
	{
		$this->visitor = Visitor::findByUid($uid);

		$this->visitor->carrier_from_data = $carrier;

		$this->visitor->save();

		return new ConnectionResource($this->visitor);
	}
}