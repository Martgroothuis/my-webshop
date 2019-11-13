<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'price'
    ];

    public function ProductOrder()
    {
        return $this->hasMany('App\ProductOrder');
    }
}
