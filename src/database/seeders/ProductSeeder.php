<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'user_id' => 1,
                'image_path' => 'products/udetokei.jpg',
                'condition' => '良好',
                'name' => 'udetokei',
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
            ],

            [
                'user_id' => 1,
                'image_path' => 'products/HDD.jpg',
                'condition' => '目立った傷や汚れなし',
                'name' => 'HDD',
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
            ],

            [
                'user_id' => 1,
                'image_path' => 'products/tamanegi.jpg',
                'condition' => 'やや傷や汚れあり',
                'name' => '玉ねぎ3束',
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
            ],

            [
                'user_id' => 1,
                'image_path' => 'products/kawagutu.jpg',
                'condition' => '状態が悪い',
                'name' => '革靴',
                'brand' => null,
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
            ],

            [
                'user_id' => 1,
                'image_path' => 'products/notepc.jpg',
                'condition' => '良好',
                'name' => 'ノートPC',
                'brand' => null,
                'description' => '高性能なノートパソコン',
                'price' => 45000,
            ],

            [
                'user_id' => 1,
                'image_path' => 'products/microphone.jpg',
                'condition' => '目立った傷や汚れなし',
                'name' => 'マイク',
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
            ],

            [
                'user_id' => 1,
                'image_path' => 'products/shoulderbag.jpg',
                'condition' => 'やや傷や汚れあり',
                'name' => 'ショルダーバッグ',
                'brand' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
            ],

            [
                'user_id' => 1,
                'image_path' => 'products/tumbler.jpg',
                'condition' => '状態が悪い',
                'name' => 'タンブラー',
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'price' => 500,
            ],

            [
                'user_id' => 1,
                'image_path' => 'products/coffeegrinder.jpg',
                'condition' => '良好',
                'name' => 'コーヒーミル',
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
            ],

            [
                'user_id' => 1,
                'image_path' => 'products/makeupset.jpg',
                'condition' => '目立った傷や汚れなし',
                'name' => 'メイクセット',
                'brand' => null,
                'description' => '便利なメイクアップセット',
                'price' => 2500,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
