<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Database\Seeders\CategorySeeder;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_profile_displays_required_information()
    {
        // CategorySeederを実行（ProductFactoryのカテゴリ自動紐付けに必要）
        $this->seed(CategorySeeder::class);

        // プロフィール情報を持つユーザーを作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image_path' => 'test_profile.jpg',
            'is_profile_completed' => true, // profile.completedミドルウェアを通過するため必須
        ]);

        // このユーザーが出品した商品を2つ作成
        $ownProduct1 = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品1',
        ]);
        $ownProduct2 = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品2',
        ]);

        // 他のユーザーが出品した商品を作成（このユーザーが購入する商品）
        $otherUser = User::factory()->create();
        $purchasedProduct1 = Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '購入商品1',
            'is_sold' => true,
        ]);
        $purchasedProduct2 = Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '購入商品2',
            'is_sold' => true,
        ]);

        // このユーザーが商品を購入した履歴を作成
        $user->orders()->create([
            'product_id' => $purchasedProduct1->id,
            'payment_method' => 'カード払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
        $user->orders()->create([
            'product_id' => $purchasedProduct2->id,
            'payment_method' => 'コンビニ払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        // ユーザーをログイン状態にする
        $this->actingAs($user);

        // 【テスト1】デフォルトのマイページにアクセス（出品商品一覧）
        $response = $this->get('/mypage');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // プロフィール画像とユーザー名が表示されていることを確認
        $response->assertViewHas('user', function ($viewUser) use ($user) {
            return $viewUser->id === $user->id
                && $viewUser->name === 'テストユーザー'
                && $viewUser->profile_image_path === 'test_profile.jpg';
        });

        // 出品商品一覧が表示されていることを確認
        $response->assertViewHas('products', function ($products) use ($ownProduct1, $ownProduct2) {
            return $products->contains('id', $ownProduct1->id)
                && $products->contains('id', $ownProduct2->id)
                && $products->count() === 2;
        });

        // 【テスト2】購入商品一覧ページにアクセス
        $response = $this->get('/mypage?page=buy');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 購入商品一覧が表示されていることを確認
        $response->assertViewHas('products', function ($products) use ($purchasedProduct1, $purchasedProduct2) {
            // 配列をCollectionに変換して検証
            $productCollection = collect($products);
            return $productCollection->contains('id', $purchasedProduct1->id)
                && $productCollection->contains('id', $purchasedProduct2->id)
                && count($products) === 2;
        });
    }
}
