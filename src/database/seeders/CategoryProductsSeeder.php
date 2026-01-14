<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class CategoryProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 腕時計（product_id: 1）→ ファッション、メンズ、アクセサリー
        Product::find(1)->categories()->attach([1, 5, 12]);

        // HDD（product_id: 2）→ 家電
        Product::find(2)->categories()->attach([2]);

        // 玉ねぎ3束（product_id: 3）→ キッチン
        Product::find(3)->categories()->attach([10]);

        // 革靴（product_id: 4）→ ファッション、メンズ
        Product::find(4)->categories()->attach([1, 5]);

        // ノートPC（product_id: 5）→ 家電
        Product::find(5)->categories()->attach([2]);

        // マイク（product_id: 6）→ 家電
        Product::find(6)->categories()->attach([2]);

        // ショルダーバッグ（product_id: 7）→ ファッション、レディース
        Product::find(7)->categories()->attach([1, 4]);

        // タンブラー（product_id: 8）→ キッチン
        Product::find(8)->categories()->attach([10]);

        // コーヒーミル（product_id: 9）→ キッチン
        Product::find(9)->categories()->attach([10]);

        // メイクセット（product_id: 10）→ コスメ、レディース
        Product::find(10)->categories()->attach([6, 4]);
    }
}
