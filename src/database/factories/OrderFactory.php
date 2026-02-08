<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * モデルに対応するファクトリーの名前
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * モデルのデフォルト状態の定義
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // payment_methodはenum型で定義された2つの値から選択
        $paymentMethods = [
            'コンビニ払い',
            'カード払い',
        ];

        // buildingは省略可能なので、時々nullを返す
        $building = $this->faker->boolean(70) ? $this->faker->secondaryAddress() : null;

        return [
            'user_id' => User::factory(),  // 自動的に新しいユーザーを作成して紐付ける
            'product_id' => Product::factory(),  // 自動的に新しい商品を作成して紐付ける
            'payment_method' => $this->faker->randomElement($paymentMethods),
            'postal_code' => $this->faker->numerify('###-####'),  // 123-4567のような形式の郵便番号を生成
            'address' => $this->faker->city() . $this->faker->streetAddress(),  // 「○○市○○町1-2-3」のような住所を生成
            'building' => $building,  // 70%の確率で建物名を生成、30%の確率でnull
        ];
    }
}
