<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    function user() {
        return $this->hasOne(User::class, 'id');
    }

    function card_offered() {
        return $this->hasOne(Card::class, 'id');
    }

    function card_wanted() {
        return $this->hasOne(Card::class, 'id');
    }
}
