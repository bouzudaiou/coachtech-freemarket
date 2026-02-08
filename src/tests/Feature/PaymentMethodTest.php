<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Database\Seeders\CategorySeeder;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // CategorySeederを実行（ProductFactoryのカテゴリ自動紐付けに必要）
        $this->seed(CategorySeeder::class);
    }

    public function test_payment_method_selection_is_displayed_on_purchase_page()
    {
        // ユーザーを作成
        $user = User::factory()->create([
            'is_profile_completed' => true, // profile.completedミドルウェアを通過するため必須
        ]);

        // 他のユーザーが出品した商品を作成
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'テスト商品',
            'price' => 15000,
        ]);

        // ユーザーをログイン状態にする
        $this->actingAs($user);

        // 商品購入画面にアクセス
        $response = $this->get('/purchase/' . $product->id);

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 支払い方法の選択肢「コンビニ払い」が表示されていることを確認
        $response->assertSee('コンビニ払い');

        // 支払い方法の選択肢「カード払い」が表示されていることを確認
        $response->assertSee('カード払い');

        // 商品代金が表示されていることを確認（¥15,000の形式）
        $response->assertSee('15,000');
    }
}
