<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'url',
        'product_version_id',
    ];

    public function Product_version()
    {
        return $this->belongsTo('App\Product_version');
    }
}
