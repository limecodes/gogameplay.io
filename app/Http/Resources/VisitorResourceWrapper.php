<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\VisitorResource;
use App\Http\Resources\VisitorCarrierListResource;

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
            return new VisitorCarrierListResource($this);
        } else {
            return new VisitorResource($this);
        }
    }
}
