<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout()
    {
        // ユーザーを作成
        $user = User::factory()->create();

        // ユーザーをログイン状態にする
        $this->actingAs($user);

        // ログアウトリクエストを送信
        $response = $this->post('/logout');

        // ログアウト後、商品一覧画面にリダイレクトされることを確認
        $response->assertRedirect('/');

        // ユーザーが認証されていないこと(ゲスト状態)を確認
        $this->assertGuest();
    }
}
