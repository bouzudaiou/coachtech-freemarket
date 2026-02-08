<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Database\Seeders\CategorySeeder;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // CategorySeederを実行（ProductFactoryのカテゴリ自動紐付けに必要）
        $this->seed(CategorySeeder::class);
    }

    public function test_liked_products_are_displayed_in_mylist()
    {
        // ログインユーザーを作成
        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        // 他のユーザー（出品者）を作成
        $seller = User::factory()->create();

        // 出品者が出品した商品を作成
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'いいねした商品',
        ]);

        // ユーザーをログイン状態にする
        $this->actingAs($user);

        // 商品にいいねを登録
        $product->likedUsers()->attach($user->id);

        // マイリスト画面にアクセス
        $response = $this->get('/?tab=mylist');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // いいねした商品が表示されていることを確認
        $response->assertSee('いいねした商品');
    }

    public function test_unliked_products_are_removed_from_mylist()
    {
        // ログインユーザーを作成
        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        // 他のユーザー（出品者）を作成
        $seller = User::factory()->create();

        // 出品者が出品した商品を作成
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'いいねを外す商品',
        ]);

        // ユーザーをログイン状態にする
        $this->actingAs($user);

        // 商品にいいねを登録
        $product->likedUsers()->attach($user->id);

        // マイリスト画面にアクセスして商品が表示されていることを確認
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertSee('いいねを外す商品');

        // いいねを解除（トグル処理）
        $this->post('/item/' . $product->id . '/like');

        // 再度マイリスト画面にアクセス
        $response = $this->get('/?tab=mylist');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // いいねを外した商品が表示されていないことを確認
        $response->assertDontSee('いいねを外す商品');
    }
}
