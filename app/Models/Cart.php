<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'user_id',
        'product_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}