<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    public function Product_version()
    {
        return $this->belongsTo('App\Product_version');
    }
    public function Waist_size()
    {
        return $this->belongsTo('App\Waist_size');
    }
    public function Length_size()
    {
        return $this->belongsTo('App\Length_size');
    }
}
