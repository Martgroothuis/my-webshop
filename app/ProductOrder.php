<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    protected $fillable = [
        'order_id',
        'product_version_id',
        'size_id',
        'ammount'
    ];

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function productVersion()
    {
        return $this->belongsTo('App\ProductVersion');
    }
}
