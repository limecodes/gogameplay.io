<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\VisitorInterface;

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

    	$this->visitoryRepository->set($ipAddress, 'non-mobile', false);

    	return response('non mobile', 200);
    }
}
