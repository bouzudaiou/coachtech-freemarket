<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    //1つのカテゴリーに登録された複数の商品（多対多）
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_products')->withTimestamps();
    }
}
