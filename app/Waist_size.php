<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Waist_size extends Model
{
    public function Size()
    {
        return $this->hasMany('App\Size');
    }
}
