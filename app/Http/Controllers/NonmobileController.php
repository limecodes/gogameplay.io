<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\VisitorInterface;
use Illuminate\Support\Facades\Config;

class NonmobileController extends Controller
{
	protected $visitorRepository;

	public function __construct(VisitorInterface $visitorRepository)
	{
		$this->visitoryRepository = $visitorRepository;
	}

    public function index(Request $request)
    {
    	$ipAddress = $request->server('GGP_REMOTE_ADDR');

    	$this->visitoryRepository->set($ipAddress, Config::get('constants.devices.non_mobile'), false);

    	return response('non mobile', 200);
    }
}
