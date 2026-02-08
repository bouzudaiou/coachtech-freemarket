<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * モデルに対応するファクトリーの名前
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * モデルのデフォルト状態の定義
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // ProductSeederとマイグレーションファイルで定義されている商品の状態から選択
        $conditions = [
            '良好',
            '目立った傷や汚れなし',
            'やや傷や汚れあり',
            '状態が悪い',
        ];

        // ブランド名のサンプル（nullableなので時々nullを返す）
        $brands = [
            null,
            'なし',
            $this->faker->company(),
        ];

        return [
            'user_id' => User::factory(),  // 自動的に新しいユーザーを作成して紐付ける
            'image_path' => 'products/' . $this->faker->word() . '.jpg',  // ProductSeederの形式に合わせる
            'condition' => $this->faker->randomElement($conditions),
            'name' => $this->faker->words(3, true),  // 2-3単語の商品名を生成
            'brand' => $this->faker->randomElement($brands),
            'description' => $this->faker->realText(100),  // 100文字程度の説明文
            'price' => $this->faker->numberBetween(100, 100000),  // 100円から10万円の範囲
            // is_soldはデフォルトfalseなので省略可能（必要に応じて明示的に指定することも可能）
        ];
    }

    /**
     * 商品作成後に自動的にカテゴリーを紐付ける
     *
     * このコールバックは、factory()->create()で商品が実際にデータベースに保存された後に実行されます。
     * ランダムに1〜3個のカテゴリーを商品に紐付けます。
     * これにより、「複数選択されたカテゴリが表示されているか」というテストケースに対応できます。
     */
    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            // データベースに既存のカテゴリーがあればそれを使用、なければ新規作成
            $categories = Category::inRandomOrder()
                ->limit($this->faker->numberBetween(1, 3))
                ->get();

            // カテゴリーが存在しない場合は新規作成
            if ($categories->isEmpty()) {
                $categories = Category::factory()->count($this->faker->numberBetween(1, 3))->create();
            }

            // 商品にカテゴリーを紐付ける
            $product->categories()->attach($categories);
        });
    }

    /**
     * 購入済み商品の状態を生成
     *
     * テストで購入済み商品が必要な場合に使用します。
     * 使用例: Product::factory()->sold()->create()
     */
    public function sold()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_sold' => true,
            ];
        });
    }
}
