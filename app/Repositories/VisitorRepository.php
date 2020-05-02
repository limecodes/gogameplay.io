<?php

namespace App\Repositories\VisitorRepository;

use Illuminate\Support\Str;
use App\Contracts\VisitorInterface;
use App\Models\Visitor;

class VisitorRepository implements VisitorInterface
{
	public function recordOrFetch($ipAddress, $device)
	{
		$visitor = Visitor::firstOrCreate(
			['ip_address' => $ipAddress, 'device' => $device],
			['uid' => (string) Str::uuid(), 'ip_address' => $ipAddress, 'device' => $device]
		);
	}
}