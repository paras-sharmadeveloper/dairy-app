<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = ['user_id', 'name', 'mobile', 'token'];

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }
    //
}
