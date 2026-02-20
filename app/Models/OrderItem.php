<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'ordered_qty',
        'delivered_qty',
        'rate_snapshot'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
