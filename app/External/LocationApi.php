<?php

namespace App\External;

use Illuminate\Support\Facades\Http;
use App\Contracts\LocationApiInterface;

class LocationApi implements LocationApiInterface {

	protected $baseUrl;
	private $apiKey;

	public function __construct()
	{
		$this->baseUrl = env('IP2LOCATION_BASE_URL');
		$this->apiKey = env('IP2LOCATION_API_KEY');
	}

	private function sendRequest()
	{
		$response = Http::get($this->baseUrl, [
			'ip' => '1.1.1.1',
			'key' => $this->apiKey,
			'package' => 'WS19'
		]);

		dd($response->json());
	}

	public function fetchLocation():void
	{
		$this->sendRequest();
	}

}