<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Comment;
use App\Models\User;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * モデルに対応するファクトリーの名前
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * モデルのデフォルト状態の定義
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),  // 自動的に新しいユーザーを作成して紐付ける
            'product_id' => Product::factory(),  // 自動的に新しい商品を作成して紐付ける
            'content' => $this->faker->realText(200),  // 200文字程度のコメント本文を生成
        ];
    }
}
