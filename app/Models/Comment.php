<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'content',
    ];

     // Mối quan hệ "n" đến "1" với mô hình User
     public function user()
     {
         return $this->belongsTo(User::class, 'user_id');
     }
 
     // Mối quan hệ "n" đến "1" với mô hình Product
     public function product()
     {
         return $this->belongsTo(Product::class, 'product_id');
     }
}