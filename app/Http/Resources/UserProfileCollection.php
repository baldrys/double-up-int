<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\UserProfile;
use App\Http\Resources\UserProfile as UserProfileResource;

class UserProfileCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        // return parent::toArray($request);
        // return [
        //     'profiles' => $this->collection
        // ];
        return [
            'profiles' =>
                $this->collection->transform(function($userProfile){
                    return [
                            'id'      => $userProfile->id,
                            'name'    => $userProfile->name,
                            'user_id' => $userProfile->user_id,
                    ];
                }),   
    ];
    }
    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
