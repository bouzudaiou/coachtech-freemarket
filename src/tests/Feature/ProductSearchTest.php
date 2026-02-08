<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Database\Seeders\CategorySeeder;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
    }

    public function testProductSearchByName()
    {
        // 1. 別ユーザー（出品者）を作成
        $seller = User::factory()->create();

        // 2. 検索にヒットする商品を作成
        $product1 = Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'テスト腕時計',
        ]);

        $product2 = Product::factory()->create([
            'user_id' => $seller->id,
            'name' => '高級テスト時計',
        ]);

        // 3. 検索にヒットしない商品を作成
        $product3 = Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'ノートパソコン',
        ]);

        // 4. 検索実行（GET /?keyword=テスト）
        $response = $this->get('/?keyword=テスト');

        // 5. 検索結果確認
        $response->assertStatus(200);
        $response->assertSee('テスト腕時計');
        $response->assertSee('高級テスト時計');
        $response->assertDontSee('ノートパソコン');
    }

    public function testSearchStatePreservedInMylist()
    {
        // 1. ユーザー作成（ログイン必要）
        $user = User::factory()->create();

        // 2. 別ユーザー（出品者）を作成
        $seller = User::factory()->create();

        // 3. いいね済み商品を作成（検索にヒット）
        $likedProduct1 = Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'いいね済みテスト商品',
        ]);
        $likedProduct1->likedUsers()->attach($user->id);

        // 4. いいね済み商品を作成（検索にヒットしない）
        $likedProduct2 = Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'いいね済みカメラ',
        ]);
        $likedProduct2->likedUsers()->attach($user->id);

        // 5. いいねしていない商品（検索にヒット）
        $notLikedProduct = Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'テスト未いいね商品',
        ]);

        // 6. マイリストで検索実行（GET /?tab=mylist&keyword=テスト）
        $response = $this->actingAs($user)->get('/?tab=mylist&keyword=テスト');

        // 7. 検索結果確認
        $response->assertStatus(200);
        $response->assertSee('いいね済みテスト商品');  // いいね済み かつ 検索ヒット
        $response->assertDontSee('いいね済みカメラ');  // いいね済み だが 検索ヒットせず
        $response->assertDontSee('テスト未いいね商品');  // 検索ヒット だが いいねなし
    }
}
