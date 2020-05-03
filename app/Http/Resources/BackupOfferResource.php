<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BackupOfferResource extends JsonResource
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
            'text' => $this->text,
            'img' => $this->img,
            'url' => $this->url
        ];
    }
}
