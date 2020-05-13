<?php

namespace App\Contracts;

interface LocationApiInterface
{
    public function getCountryAndDetectCarrier($ipAddress):array;
    public function getCountryOnly($ipAddress):array;
}
