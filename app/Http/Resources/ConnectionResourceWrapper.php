<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ConnectionResource;
use App\Http\Resources\CarrierResource;

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
        if ( ($this->device == 'android') && ($this->mobile_connection == true) && ($this->carrier_from_data == null) ) {
            return [
                'visitor' => new ConnectionResource($this),
                'carriers_by_country' => CarrierResource::collection($this->country->mobileNetwork)
            ];
        } else if ( ($this->device == 'ios') && ($this->mobile_connection == false) ) {
            return [
                'visitor' => new ConnectionResource($this),
                'carriers_by_country' => CarrierResource::collection($this->country->mobileNetwork)
            ];
        } else {
            return new ConnectionResource($this);
        }
    }
}
