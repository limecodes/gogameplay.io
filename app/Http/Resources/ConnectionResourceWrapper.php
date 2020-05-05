<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\ConnectionResource;
use App\Http\Resources\ConnectionCarrierListResource;

class ConnectionResourceWrapper extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->carrier_from_data == null) {
            return new ConnectionCarrierListResource($this);
        } else {
            return new ConnectionResource($this);
        }
    }
}
