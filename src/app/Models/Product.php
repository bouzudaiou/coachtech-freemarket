<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'image_path', 'condition', 'name', 'brand', 'description', 'price'];

    //出品した登録者との関係（多対一）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //いいねをした登録者（多対多）
    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    //商品に対する複数のコメント（一対多）
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    //1つの商品に複数登録されるカテゴリー（多対多）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_products')
            ->withTimestamps();
    }
}
