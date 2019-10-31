<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Length_size extends Model
{
    public function Size()
    {
        return $this->hasMany('App\Size');
    }
}
