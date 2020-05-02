<?php

namespace App\External;

use Illuminate\Support\Facades\Http;

interface LocationApiInterface {
	public function getCountryAndDetectCarrier($ipAddress):array;
	public function getCountryOnly($ipAddress):array;
}

class LocationApi implements LocationApiInterface {

	protected $baseUrl;
	private $apiKey;

	public function __construct($baseUrl, $apiKey)
	{
		$this->baseUrl = $baseUrl;
		$this->apiKey = $apiKey;
	}

	private function sendRequest($ipAddress, $package)
	{
		$response = Http::get($this->baseUrl, [
			'ip' => $ipAddress,
			'key' => $this->apiKey,
			'package' => $package
		]);

		return $response->json();
	}

	public function getCountryAndDetectCarrier($ipAddress):array
	{
		$data = $this->sendRequest($ipAddress, 'WS19');

		return [
			'iso_code' => $data['country_code'],
			'carrier' => ($data['mobile_brand'] !== '-') ? $data['mobile_brand'] : null
		];
	}

	public function getCountryOnly($ipAddress):array
	{
		$data = $this->sendRequest($ipAddress, 'WS1');

		return [
			'iso_code' => $data['country_code']
		];
	}

}