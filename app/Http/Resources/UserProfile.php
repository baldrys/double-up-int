<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfile extends JsonResource
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
            'profile' => [
                'id' => $this->id,
                'name' => $this->name,
                'user_id' => $this->user_id,
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
