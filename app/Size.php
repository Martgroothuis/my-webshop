<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = [
        'product_version_id',
        'waist_size_id',
        'length_size_id',
        'stock'
    ];

    public function productVersion()
    {
        return $this->belongsTo('App\ProductVersion');
    }
    public function waistSize()
    {
        return $this->belongsTo('App\WaistSize');
    }
    public function lengthSize()
    {
        return $this->belongsTo('App\LengthSize');
    }
}
