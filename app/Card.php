<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable=['name','category_id', 'subcategory_id', 'short_description', 'description', 'image'];

    public function category()
    {

        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['qty']);
    }

    public function offers_wanted(){
        return $this->belongsToMany(Offer::class, 'offers', 'card_wanted', 'id');
    }

    public function offers_offered() {
        return $this->belongsToMany(Offer::class, 'offers', 'card_offered', 'id');
    }
}
