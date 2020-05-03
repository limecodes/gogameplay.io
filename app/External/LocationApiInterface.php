<?php

namespace App\External;

interface LocationApiInterface {
	public function getCountryAndDetectCarrier($ipAddress):array;
	public function getCountryOnly($ipAddress):array;
}