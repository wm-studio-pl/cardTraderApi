<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $fillable=['name', 'order', 'style'];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
