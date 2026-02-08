<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_edit_page_displays_user_information()
    {
        // 既存のプロフィール情報を持つユーザーを作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image_path' => 'test_profile.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト町1-2-3',
            'building' => 'テストビル101',
        ]);

        // ユーザーをログイン状態にする
        $this->actingAs($user);

        // プロフィール編集画面にアクセス
        $response = $this->get('/mypage/profile');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // ビューに正しいユーザー情報が渡されていることを確認
        $response->assertViewHas('user', function ($viewUser) use ($user) {
            return $viewUser->id === $user->id
                && $viewUser->name === 'テストユーザー'
                && $viewUser->profile_image_path === 'test_profile.jpg'
                && $viewUser->postal_code === '123-4567'
                && $viewUser->address === '東京都渋谷区テスト町1-2-3'
                && $viewUser->building === 'テストビル101';
        });
    }
}
