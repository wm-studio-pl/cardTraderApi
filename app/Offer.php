<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    function card_offered() {
        return $this->hasOne(Card::class, 'id', 'card_offered');
    }

    function card_wanted() {
        return $this->hasOne(Card::class, 'id', 'card_wanted');
    }
}
