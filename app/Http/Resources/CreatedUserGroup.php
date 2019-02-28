<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CreatedUserGroup extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'created_group' => [
                'id' => $this->id,
                'name' => $this->name,
            ],
        ];
    }
    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
