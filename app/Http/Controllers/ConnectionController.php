<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ConnectionRequest;
use App\Contracts\VisitorInterface;

class ConnectionController extends Controller
{
	protected $visitorRepository;

	public function __construct(VisitorInterface $visitorRepository)
	{
		$this->visitorRepository = $visitorRepository;
	}


    public function connectionChanged(ConnectionRequest $request)
    {
    	$connectionRequestValidated = $request->validated();

    	$ipAddress = $request->server('GGP_REMOTE_ADDR');
    	$uid = $connectionRequestValidated['uid'];
    	$device = $connectionRequestValidated['device'];

    	$response = $this->visitorRepository->connectionChanged($uid, $device, $ipAddress);

 		return response()->json($response, 200);
    }
}
