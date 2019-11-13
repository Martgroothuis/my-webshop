<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name'
    ];

    public function productVersion()
    {
        return $this->hasMany('App\ProductVersion');
    }
}
