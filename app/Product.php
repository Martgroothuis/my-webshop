<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name'
    ];

    public function Product_version()
    {
        return $this->hasMany('App\Product_version');
    }
}
