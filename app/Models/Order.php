<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'shop_id',
        'date',
        'status',
        'source',
        'public_token'
    ];
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
