<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'description'=> $this->description,
            'phone'=> $this->phone,
            'email'=> $this->email,
            'website'=> $this->website,
            'logo'=> $this->logo,
            'location'=> $this->location,
            'school_category'=> new SchoolCategoryResource($this->school_category),
        ];
    }
}
