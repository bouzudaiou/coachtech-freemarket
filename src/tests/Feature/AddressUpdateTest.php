<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Database\Seeders\CategorySeeder;

class AddressUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
    }

    public function testAddressUpdateReflectedInPurchaseScreen()
    {
        // 1. ユーザー作成（is_profile_completed => true）
        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        // 2. 商品作成（別ユーザーの出品）
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        // 3. 住所変更（POST /purchase/address/{id}）
        $addressData = [
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストビル101',
        ];

        $response = $this->actingAs($user)->post("/purchase/address/{$product->id}", $addressData);

        // 4. 購入画面にリダイレクト確認
        $response->assertRedirect("/purchase/{$product->id}");

        // 5. 購入画面で住所表示確認（GET /purchase/{id}）
        $response = $this->actingAs($user)->get("/purchase/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区1-2-3');
        $response->assertSee('テストビル101');
    }

    public function testAddressSavedWithOrder()
    {
        // 1. ユーザー作成
        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        // 2. 商品作成
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        // 3. 住所データ
        $addressData = [
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市中央区5-6-7',
            'building' => 'サンプルマンション202',
        ];

        // 4. ordersテーブルに直接レコード作成（Stripe連携を避けるため）
        $order = Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'payment_method' => 'カード払い',
            'postal_code' => $addressData['postal_code'],
            'address' => $addressData['address'],
            'building' => $addressData['building'],
        ]);

        // 5. データベース確認
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市中央区5-6-7',
            'building' => 'サンプルマンション202',
        ]);

        // 6. リレーション確認
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals($product->id, $order->product_id);
    }
}
