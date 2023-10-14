<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'payment',
        'total',
        'user_id',
    ];
    // Define a one-to-many relationship with CartOrder
    public function cart_orders()
    {
        return $this->hasMany(Cart_Order::class);
    }

    // Define a many-to-one relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}