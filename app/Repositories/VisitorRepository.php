<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Contracts\VisitorInterface;
use App\External\LocationApi;
use App\Http\Resources\VisitorResource;
use App\Http\Resources\CarrierResource;
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

		// There must be a better way to do this.
		if ( ($this->visitor->mobile_connection) && ($this->visitor->carrier_from_data == null) ) {
			$ret = [
				'visitor' => new VisitorResource($this->visitor),
				'carriers_by_country' => CarrierResource::collection($this->visitor->country->mobileNetwork)
			];
		} else {
			$ret = new VisitorResource($this->visitor);
		}

		return $ret;
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
}