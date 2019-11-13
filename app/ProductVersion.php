<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVersion extends Model
{
    protected $fillable = [
        'price',
        'product_id',
        'color_id'
    ];

    public function image()
    {
        return $this->hasMany('App\Image');
    }

    public function size()
    {
        return $this->hasMany('App\Size');
    }

    public function waist()
    {
        return $this->belongsToMany('App\WaistSize', 'sizes');
    }

    public function length()
    {
        return $this->belongsToMany('App\LengthSize', 'sizes');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function color()
    {
        return $this->belongsTo('App\Color');
    }

    public function productOrder()
    {
        return $this->hasMany('App\ProductOrder');
    }
}
