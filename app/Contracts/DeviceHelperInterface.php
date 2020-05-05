<?php 

namespace App\Contracts;

interface DeviceHelperInterface {
	public function getDataAndroid():array;
	public function getDataApple():array;
}
