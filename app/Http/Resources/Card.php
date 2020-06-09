<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Card extends JsonResource
{
    public $preserveKeys = true;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'short'=>$this->short_description,
            'description'=>$this->description,
            'category'=>$this->category,
            'subcategory'=>$this->subcategory,
            'image'=> $this->getImageUrl($this->image),
            'qty' => $this->whenPivotLoaded('card_user', function () {
                return $this->pivot->qty;
            }),
        ];
    }

    public function with($request){
        return [
            'version' => config('app.name'). ' ver: ' . config('app.app_ver'),
            'author_url'=> url(config('app.app_author')),
        ];
    }

    private function getImageUrl(string $imageName) {
        return $this->prepareImageUrl($imageName);
    }

    private function prepareImageUrl(string $imageUrl) {
        if ($this->isFullAddress($imageUrl))
            return $imageUrl;
        else
            return url("/")
                . "/"
                . config('app.img_folder')
                . "/"
                . $imageUrl;
    }

    private function isFullAddress(string $url) {
        return (substr($url, 0, 5) == 'http:')
            || (substr($url, 0, 4) == 'ftp:');
    }
}
