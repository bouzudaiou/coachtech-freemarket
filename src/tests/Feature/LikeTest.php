<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Database\Seeders\CategorySeeder;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // CategorySeederを実行（ProductFactoryのカテゴリ自動紐付けに必要）
        $this->seed(CategorySeeder::class);
    }

    public function test_user_can_like_a_product()
    {
        // ユーザーを作成
        $user = User::factory()->create([
            'is_profile_completed' => true, // profile.completedミドルウェアを通過するため必須
        ]);

        // 商品を作成
        $product = Product::factory()->create([
            'name' => 'テスト商品',
        ]);

        // ユーザーをログイン状態にする
        $this->actingAs($user);

        // いいねリクエストを送信（トグル処理 - いいね登録）
        $response = $this->post('/item/' . $product->id . '/like');

        // 商品詳細ページにリダイレクトされることを確認
        $response->assertRedirect('/item/' . $product->id);

        // データベースにいいねが保存されていることを確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_user_can_unlike_a_product()
    {
        // ユーザーを作成
        $user = User::factory()->create([
            'is_profile_completed' => true, // profile.completedミドルウェアを通過するため必須
        ]);

        // 商品を作成
        $product = Product::factory()->create([
            'name' => 'テスト商品',
        ]);

        // ユーザーをログイン状態にする
        $this->actingAs($user);

        // 事前にいいねを登録しておく
        $product->likedUsers()->attach($user->id);

        // データベースにいいねが存在することを確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // いいねリクエストを送信（トグル処理 - いいね解除）
        $response = $this->post('/item/' . $product->id . '/like');

        // 商品詳細ページにリダイレクトされることを確認
        $response->assertRedirect('/item/' . $product->id);

        // データベースからいいねが削除されていることを確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
}
