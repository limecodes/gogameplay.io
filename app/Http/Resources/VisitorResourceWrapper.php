<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\VisitorResource;
use App\Http\Resources\CarrierResource;

class VisitorResourceWrapper extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ( ($this->mobile_connection) && ($this->carrier_from_data == null) ) {
            return [
                'visitor' => new VisitorResource($this),
                'carriers_by_country' => CarrierResource::collection($this->country->mobileNetwork)
            ];
        } else {
            return new VisitorResource($this);
        }
    }
}
