<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Contracts\VisitorInterface;
use App\Contracts\LocationApiInterface;
use App\Http\Resources\VisitorResource;
use App\Models\Visitor;

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
		$this->locationApi->fetchLocation();

		if ( ($this->visitor->connection) && (!$this->visitor->country_id) ) {
			
		}
	}

	private function setApple():void
	{

	}

	public function set($ipAddress, $device, $connection):VisitorResource
	{
		$this->visitor = Visitor::firstOrCreate(
			['ip_address' => $ipAddress, 'device' => $device],
			['uid' => (string) Str::uuid(), 'ip_address' => $ipAddress, 'device' => $device, 'mobile_connection' => $connection]
		);

		if ($this->visitor->device == 'android') {
			$this->setAndroid();
		} else if ($this->visitor->device == 'ios') {
			$this->setApple();
		}

		return new VisitorResource($this->visitor);
	}
}