<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Manufacturer extends Model
{
    function products() {
       return $this->hasMany(Product::class);
       // return $this->hasMany('App\Models\Product');
    }

}
