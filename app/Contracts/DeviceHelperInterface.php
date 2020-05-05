<?php 

namespace App\Contracts;

use App\Models\Visitor;

interface DeviceHelperInterface {
	public function getDataAndroid(Visitor $visitor):Visitor;
	public function getDataApple(Visitor $visitor):Visitor;
	public function getDataNonMobile(Visitor $visitor):Visitor;
}
