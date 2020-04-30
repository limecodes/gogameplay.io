<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VisitorRequest;
use App\Models\Visitor;
use Illuminate\Support\Str;

class VisitorController extends Controller
{
	private function recordOrFetchVisitor($ipAddress, $device)
	{
		$visitor = Visitor::firstOrCreate(
			['ip_address' => $ipAddress, 'device' => $device],
			['uid' => (string) Str::uuid(), 'ip_address' => $ipAddress, 'device' => $device]
		);

		return $visitor;
	}

	private function androidSet($connection, $ipAddress)
	{
		$visitor = $this->recordOrFetchVisitor($ipAddress, 'android');

		if ($connection) {
			// Fetch the API and get the carrier
		} else {

		}

		return $visitor;
	}

	private function appleSet($connection)
	{

	}

    public function set(VisitorRequest $request)
    {
    	$visitorRequestValidated = $request->validated();

    	$device = $visitorRequestValidated['device'];
    	$connection = $visitorRequestValidated['connection'];

    	if ($visitorRequestValidated['device'] == 'android') {
    		$this->androidSet($connection);
    	} else if ($visitorRequestValidated['device'] == 'ios') {
    		$this->appleSet($connection);
    	}

    	return response()->json(['ok'], 200);
    }
}
