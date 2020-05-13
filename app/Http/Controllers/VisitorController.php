<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisitorRequest;
use App\Contracts\VisitorInterface;

class VisitorController extends Controller
{
	protected $visitorRepository;

	public function __construct(VisitorInterface $visitorRepository)
	{
		$this->visitorRepository = $visitorRepository;
	}

    public function set(VisitorRequest $request)
    {
    	$visitorRequestValidated = $request->validated();

    	$ipAddress = $request->server('GGP_REMOTE_ADDR');
    	$device = $visitorRequestValidated['device'];
    	$connection = $visitorRequestValidated['connection'];

    	$response = $this->visitorRepository->set($ipAddress, $device, $connection);

    	return response()->json($response, 200);
    }
}
