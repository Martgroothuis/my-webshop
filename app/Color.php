<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    public function Product_version()
    {
        return $this->hasMany('App\Product_version');
    }
}
