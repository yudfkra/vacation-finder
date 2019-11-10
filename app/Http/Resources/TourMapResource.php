<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TourMapResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'content' => view('tour.content', ['tour' => $this->resource])->render(),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'image_url' => $this->image_url,
        ];
    }
}
