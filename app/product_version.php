<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_version extends Model
{
    protected $fillable = [
        'price',
        'product_id',
        'color_id'
    ];

    public function Image()
    {
        return $this->hasMany('App\Image');
    }

    public function Size()
    {
        return $this->hasMany('App\Size');
    }

    public function Waist()
    {
        return $this->belongsToMany('App\Waist_size', 'sizes');
    }

    public function Length()
    {
        return $this->belongsToMany('App\Length_size', 'sizes');
    }

    public function Product()
    {
        return $this->belongsTo('App\Product');
    }

    public function Color()
    {
        return $this->belongsTo('App\Color');
    }
}
