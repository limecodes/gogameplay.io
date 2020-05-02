<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Contracts\VisitorInterface;
use App\Http\Resources\VisitorResource;
use App\Models\Visitor;

class VisitorRepository implements VisitorInterface
{
	protected $visitor;

	private function setAndroid():void
	{
		dd($this->visitor);
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