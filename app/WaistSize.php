<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WaistSize extends Model
{
    protected $fillable = [
        'size'
    ];

    public function size()
    {
        return $this->hasMany('App\Size');
    }
}
