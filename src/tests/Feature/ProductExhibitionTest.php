<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Database\Seeders\CategorySeeder;

class ProductExhibitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_created_with_required_information()
    {
        // ストレージをフェイクに設定（実際のファイル保存をテスト用に置き換え）
        Storage::fake('public');

        // CategorySeederを実行（カテゴリのexists検証に必要）
        $this->seed(CategorySeeder::class);

        // テスト用ユーザーを作成
        $user = User::factory()->create([
            'is_profile_completed' => true, // profile.completedミドルウェアを通過するため必須
        ]);

        // ユーザーをログイン状態にする
        $this->actingAs($user);

        // テスト用のカテゴリIDを取得（2つ選択する想定）
        $categories = Category::take(2)->pluck('id')->toArray();

        // テスト用の画像ファイルを作成（GD拡張不要）
        $image = UploadedFile::fake()->create('test_product.jpg', 100, 'image/jpeg');

        // 商品出品データ
        $productData = [
            'name' => 'テスト商品',
            'description' => 'これはテスト用の商品説明です。',
            'image_path' => $image,
            'category_id' => $categories,
            'condition' => '良好',
            'price' => 10000,
            'brand' => 'テストブランド',
        ];

        // 商品出品リクエストを送信
        $response = $this->post('/sell', $productData);

        // 商品一覧画面にリダイレクトされることを確認
        $response->assertRedirect('/');

        // データベースに商品が保存されていることを確認
        $this->assertDatabaseHas('products', [
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'description' => 'これはテスト用の商品説明です。',
            'condition' => '良好',
            'price' => 10000,
            'brand' => 'テストブランド',
        ]);

        // 画像がストレージに保存されていることを確認
        Storage::disk('public')->assertExists('products/' . $image->hashName());

        // 作成された商品を取得
        $product = Product::where('name', 'テスト商品')->first();

        // カテゴリが中間テーブルに正しく紐付けられていることを確認
        foreach ($categories as $categoryId) {
            $this->assertDatabaseHas('category_products', [
                'product_id' => $product->id,
                'category_id' => $categoryId,
            ]);
        }

        // 商品に紐付けられたカテゴリ数が正しいことを確認
        $this->assertEquals(2, $product->categories()->count());
    }
}
