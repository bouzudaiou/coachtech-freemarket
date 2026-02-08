<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Comment;
use Database\Seeders\CategorySeeder;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // CategorySeederを実行（ProductFactoryのカテゴリ自動紐付けに必要）
        $this->seed(CategorySeeder::class);
    }

    public function test_product_detail_displays_required_information()
    {
        // 商品を作成（ProductFactoryが自動的にカテゴリを紐付ける）
        $product = Product::factory()->create([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 10000,
            'description' => 'これはテスト用の商品説明です。',
            'condition' => '良好',
        ]);

        // コメントを投稿したユーザーを作成
        $commentUser1 = User::factory()->create([
            'name' => 'コメントユーザー1',
            'profile_image_path' => 'comment_user1.jpg',
        ]);
        $commentUser2 = User::factory()->create([
            'name' => 'コメントユーザー2',
            'profile_image_path' => 'comment_user2.jpg',
        ]);

        // コメントを作成
        Comment::factory()->create([
            'product_id' => $product->id,
            'user_id' => $commentUser1->id,
            'content' => 'これは1つ目のコメントです。',
        ]);
        Comment::factory()->create([
            'product_id' => $product->id,
            'user_id' => $commentUser2->id,
            'content' => 'これは2つ目のコメントです。',
        ]);

        // いいねをしたユーザーを作成
        $likeUser1 = User::factory()->create();
        $likeUser2 = User::factory()->create();
        $likeUser3 = User::factory()->create();

        // いいねを作成
        $product->likedUsers()->attach($likeUser1->id);
        $product->likedUsers()->attach($likeUser2->id);
        $product->likedUsers()->attach($likeUser3->id);

        // 商品詳細画面にアクセス
        $response = $this->get('/item/' . $product->id);

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 商品名が表示されていることを確認
        $response->assertSee('テスト商品');

        // ブランド名が表示されていることを確認
        $response->assertSee('テストブランド');

        // 価格が表示されていることを確認（¥10,000の形式）
        $response->assertSee('10,000');

        // 商品説明が表示されていることを確認
        $response->assertSee('これはテスト用の商品説明です。');

        // 商品の状態が表示されていることを確認
        $response->assertSee('良好');

        // いいね数が表示されていることを確認
        $response->assertSee('3'); // 3人がいいね

        // コメント数が表示されていることを確認
        $response->assertSee('2'); // 2件のコメント

        // コメントユーザー名が表示されていることを確認
        $response->assertSee('コメントユーザー1');
        $response->assertSee('コメントユーザー2');

        // コメント内容が表示されていることを確認
        $response->assertSee('これは1つ目のコメントです。');
        $response->assertSee('これは2つ目のコメントです。');
    }

    public function test_product_detail_displays_multiple_categories()
    {
        // 商品を作成（ProductFactoryが自動的に1-3個のカテゴリを紐付ける）
        $product = Product::factory()->create([
            'name' => 'マルチカテゴリ商品',
        ]);

        // 商品詳細画面にアクセス
        $response = $this->get('/item/' . $product->id);

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 商品に紐付けられたカテゴリを取得
        $categories = $product->categories;

        // 各カテゴリ名が表示されていることを確認
        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }

        // カテゴリが少なくとも1つ以上存在することを確認
        $this->assertGreaterThanOrEqual(1, $categories->count());
    }
}
