<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * メールアドレス未入力時のエラー表示
     *
     * @test
     * @return void
     */
    public function test_email_is_required()
    {
        // メールアドレスを空にしてログインリクエストを送信
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        // バリデーションエラーでリダイレクトされることを確認
        $response->assertStatus(302);

        // エラーメッセージが表示されることを確認
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください'
        ]);
    }

    /**
     * パスワード未入力時のエラー表示
     *
     * @test
     * @return void
     */
    public function test_password_is_required()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください'
        ]);
    }

    /**
     * 登録されていないメールアドレスでのログイン試行
     *
     * @test
     * @return void
     */
    public function test_login_fails_with_unregistered_email()
    {
        // データベースに存在しないメールアドレスでログインを試みる
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        // 認証失敗でリダイレクトされることを確認
        $response->assertStatus(302);

        // 認証エラーがセッションに保存されることを確認
        $response->assertSessionHasErrors();

        // ユーザーが認証されていないことを確認
        $this->assertGuest();
    }

    /**
     * 全項目正しく入力された場合のログイン成功
     *
     * @test
     * @return void
     */
    public function test_user_can_login_with_valid_credentials()
    {
        // プロフィール完了済みのテストユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_profile_completed' => true,  // プロフィール完了済みに設定
        ]);

        // 正しいメールアドレスとパスワードでログイン
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // 商品一覧画面にリダイレクトされることを確認
        // プロフィール完了済みユーザーは/にリダイレクトされる
        $response->assertRedirect('/mypage/profile');

        // ユーザーが認証されていることを確認
        $this->assertAuthenticatedAs($user);
    }
}
