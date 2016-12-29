<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    public function meal(){
        return $this->belongsTo('App\Meal');
    }

}
