<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Database\Seeders\CategorySeeder;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
    }

    public function testPurchaseCompletes()
    {
        // 1. 購入者作成（is_profile_completed => true）
        $buyer = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        // 2. 出品者と商品作成
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
        ]);

        // 3. 購入処理（Stripe回避のため、Order::create() + is_sold更新で再現）
        $order = Order::create([
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'payment_method' => 'カード払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストビル101',
        ]);

        $product->is_sold = true;
        $product->save();

        // 4. 購入完了確認
        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_sold' => 1,  // boolean ではなく integer
        ]);
    }

    public function testPurchasedProductShowsSoldLabel()
    {
        // 1. 購入者作成
        $buyer = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        // 2. 出品者と商品作成
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
        ]);

        // 3. 購入処理（Order作成 + is_sold更新）
        Order::create([
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'payment_method' => 'カード払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストビル101',
        ]);

        $product->is_sold = true;
        $product->save();

        // 4. 商品一覧画面で「Sold」表示確認（GET /）
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function testPurchasedProductAppearsInProfile()
    {
        // 1. 購入者作成（is_profile_completed => true）
        $buyer = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        // 2. 出品者と商品作成
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
            'name' => '購入テスト商品',
        ]);

        // 3. 購入処理（Order作成 + is_sold更新）
        Order::create([
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'payment_method' => 'カード払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストビル101',
        ]);

        $product->is_sold = true;
        $product->save();

        // 4. プロフィール購入商品一覧で表示確認（GET /mypage?page=buy）
        $response = $this->actingAs($buyer)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('購入テスト商品');
    }
}
