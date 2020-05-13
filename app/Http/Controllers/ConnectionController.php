<?php

namespace App\Http\Controllers;

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

        // TODO, In tests I'm passing the device and also in the front end
        // Is there every going to be a case when device differs by uid from the one that's already recorded? I don't think so
        $response = $this->visitorRepository->connectionChanged($uid, $ipAddress);

        return response()->json($response, 200);
    }
}
