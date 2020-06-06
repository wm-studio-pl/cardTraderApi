<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class  Category extends JsonResource
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
        ];
    }

    public function with($request){
        return [
            'version' => config('app.name'). ' ver: ' . config('app.app_ver'),
            'author_url'=> url(config('app.app_author')),
        ];
    }
}
