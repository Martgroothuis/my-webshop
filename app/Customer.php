<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'sir_name',
        'infix',
        'last_name',
        'postal_code',
        'adress',
        'phone_number'
    ];

    public function user()
    {
        return $this->hasOne('App\User');
    }
}
