<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\VisitorResource;
use App\Http\Resources\CarrierResource;

class VisitorCarrierListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'visitor' => new VisitorResource($this),
            'carriers_by_country' => CarrierResource::collection($this->country->mobileNetwork)
        ];
    }
}
