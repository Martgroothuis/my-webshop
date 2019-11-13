<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LengthSize extends Model
{
    protected $fillable = [
        'size'
    ];

    public function size()
    {
        return $this->hasMany('App\Size');
    }
}
