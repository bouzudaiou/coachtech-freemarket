<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Comment;
use Database\Seeders\CategorySeeder;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // CategorySeederを実行（ProductFactoryのカテゴリ自動紐付けに必要）
        $this->seed(CategorySeeder::class);
    }

    public function test_comment_can_be_submitted_on_product_detail_page()
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

        // コメントデータ
        $commentData = [
            'content' => 'これはテスト用のコメントです。',
        ];

        // コメント送信リクエストを送信
        $response = $this->post('/item/' . $product->id . '/comment', $commentData);

        // 商品詳細ページにリダイレクトされることを確認
        $response->assertRedirect('/item/' . $product->id);

        // データベースにコメントが保存されていることを確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'content' => 'これはテスト用のコメントです。',
        ]);
    }

    public function test_comments_display_user_names_and_content()
    {
        // 自分のユーザーを作成
        $myUser = User::factory()->create([
            'name' => '自分のユーザー',
            'profile_image_path' => 'my_user.jpg',
        ]);

        // 他人のユーザーを作成
        $otherUser = User::factory()->create([
            'name' => '他人のユーザー',
            'profile_image_path' => 'other_user.jpg',
        ]);

        // 商品を作成
        $product = Product::factory()->create([
            'name' => 'テスト商品',
        ]);

        // 自分のコメントを作成
        Comment::factory()->create([
            'user_id' => $myUser->id,
            'product_id' => $product->id,
            'content' => '自分のコメント内容です。',
        ]);

        // 他人のコメントを作成
        Comment::factory()->create([
            'user_id' => $otherUser->id,
            'product_id' => $product->id,
            'content' => '他人のコメント内容です。',
        ]);

        // 商品詳細ページにアクセス
        $response = $this->get('/item/' . $product->id);

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 自分のコメントユーザー名が表示されていることを確認
        $response->assertSee('自分のユーザー');

        // 自分のコメント内容が表示されていることを確認
        $response->assertSee('自分のコメント内容です。');

        // 他人のコメントユーザー名が表示されていることを確認
        $response->assertSee('他人のユーザー');

        // 他人のコメント内容が表示されていることを確認
        $response->assertSee('他人のコメント内容です。');
    }

    public function testCommentValidationFailsWhenContentIsEmpty()
    {
        // 1. ユーザー作成（is_profile_completed => true）
        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        // 2. 商品作成
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        // 3. 空のコメントを送信（POST /item/{id}/comment）
        $response = $this->actingAs($user)->post("/item/{$product->id}/comment", [
            'content' => '',  // 空のコメント
        ]);

        // 4. バリデーションエラー確認
        $response->assertSessionHasErrors('content');

        // 5. エラーメッセージ確認
        $errors = session('errors');
        $this->assertTrue($errors->has('content'));
    }

    public function testCommentValidationFailsWhenContentExceeds255Characters()
    {
        // 1. ユーザー作成（is_profile_completed => true）
        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        // 2. 商品作成
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        // 3. 256文字のコメントを送信
        $longComment = str_repeat('あ', 256);  // 256文字

        $response = $this->actingAs($user)->post("/item/{$product->id}/comment", [
            'content' => $longComment,
        ]);

        // 4. バリデーションエラー確認
        $response->assertSessionHasErrors('content');

        // 5. エラーメッセージ確認
        $errors = session('errors');
        $this->assertTrue($errors->has('content'));
    }
}
