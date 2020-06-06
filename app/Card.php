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
}
