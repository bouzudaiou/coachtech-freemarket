<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'payment_method', 'postal_code', 'address', 'building'];

    //誰が購入したのか（多対一）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //どの商品を購入したのか（多対一）
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
