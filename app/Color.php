<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'name',
        'waterless',
        'rise'
    ];
    public function productVersion()
    {
        return $this->hasMany('App\ProductVersion');
    }
}
