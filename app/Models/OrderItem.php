<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //
    protected $table = "order_items";
    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
     public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
