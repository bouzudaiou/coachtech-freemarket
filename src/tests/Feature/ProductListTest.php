<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\CategorySeeder;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * テストのセットアップ
     *
     * 各テストメソッド実行前に自動的に呼ばれます。
     * CategorySeederを実行して、実際のアプリケーションと同じ14種類のカテゴリーを作成します。
     */
    protected function setUp(): void
    {
        parent::setUp();

        // CategorySeederを実行して14種類のカテゴリーをデータベースに作成
        $this->seed(CategorySeeder::class);
    }

    /**
     * 全商品を取得できる
     *
     * @test
     * @return void
     */
    public function test_all_products_can_be_retrieved()
    {
        // テストデータの準備：3つの商品を作成
        // ProductFactoryが自動的にユーザーとカテゴリーも作成・紐付けする
        $products = Product::factory()->count(3)->create();

        // 商品一覧ページにGETリクエストを送信
        $response = $this->get('/');

        // レスポンスが成功（200 OK）であることを確認
        $response->assertStatus(200);

        // 作成した各商品の名前がレスポンスに含まれていることを確認
        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    public function test_sold_products_display_sold_label()
    {
        // 通常の商品を作成（is_sold: false）
        $normalProduct = Product::factory()->create([
            'name' => '通常商品',
        ]);

        // 購入済み商品を作成（is_sold: true）
        $soldProduct = Product::factory()->sold()->create([
            'name' => '購入済み商品',
        ]);

        // 商品一覧画面にアクセス
        $response = $this->get('/');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 通常商品が表示されていることを確認
        $response->assertSee('通常商品');

        // 購入済み商品が表示されていることを確認
        $response->assertSee('購入済み商品');

        // 購入済み商品に「Sold」ラベルが表示されていることを確認
        $response->assertSee('Sold');
    }

    public function test_own_products_are_not_displayed()
    {
        // ログインユーザーを作成
        $user = User::factory()->create();

        // ログインユーザーが出品した商品を作成
        $ownProduct = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品',
        ]);

        // 他のユーザーが出品した商品を作成
        $otherUser = User::factory()->create();
        $otherProduct = Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品',
        ]);

        // ユーザーをログイン状態にする
        $this->actingAs($user);

        // 商品一覧画面にアクセス
        $response = $this->get('/');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 他人の商品が表示されていることを確認
        $response->assertSee('他人の商品');

        // 自分の商品が表示されていないことを確認
        $response->assertDontSee('自分の商品');
    }
}
