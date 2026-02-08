<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 名前未入力時のエラー表示
     *
     * @test
     * @return void
     */
    public function test_name_is_required()
    {
        // 名前を空にして会員登録リクエストを送信
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // バリデーションエラーでリダイレクトされることを確認
        $response->assertStatus(302);

        // エラーメッセージが表示されることを確認
        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください'
        ]);

        // ユーザーがデータベースに保存されていないことを確認
        $this->assertDatabaseMissing('users', [
            'email' => 'test@example.com'
        ]);
    }

    /**
     * メールアドレス未入力時のエラー表示
     *
     * @test
     * @return void
     */
    public function test_email_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください'
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'テストユーザー'
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
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください'
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'test@example.com'
        ]);
    }

    /**
     * パスワードが7文字以下の場合のエラー表示
     *
     * @test
     * @return void
     */
    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'pass123',  // 7文字
            'password_confirmation' => 'pass123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください'
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'test@example.com'
        ]);
    }

    /**
     * パスワードと確認用パスワードが一致しない場合のエラー表示
     *
     * @test
     * @return void
     */
    public function test_password_confirmation_must_match()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',  // passwordと異なる
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password_confirmation');

        $this->assertDatabaseMissing('users', [
            'email' => 'test@example.com'
        ]);
    }

    /**
     * 全項目正しく入力された場合の会員登録成功
     *
     * @test
     * @return void
     */
    public function test_user_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // プロフィール設定画面にリダイレクトされることを確認
        // EnsureProfileCompletedミドルウェアにより、会員登録成功後は/mypage/profileにリダイレクトされる
        $response->assertRedirect('/mypage/profile');

        // ユーザーがデータベースに保存されていることを確認
        $this->assertDatabaseHas('users', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);

        // is_profile_completedがfalse（デフォルト値）であることを確認
        $user = User::where('email', 'test@example.com')->first();
        $this->assertFalse($user->is_profile_completed);
    }
}
