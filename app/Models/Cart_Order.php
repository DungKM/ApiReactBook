<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart_Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity',
        'product_name',
        'product_image',
        'product_price',
        'order_id',
        'product_id'
    ];

    public function cart_order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}