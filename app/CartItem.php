<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    public function cart(){
        return $this->hasMany('App\Cart');
    }

    public function size(){
        return $this->belongsTo('App\Size');
    }

}
