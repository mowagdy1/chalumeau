<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function sizes(){
        return $this->hasMany('App\Size');
    }

}
