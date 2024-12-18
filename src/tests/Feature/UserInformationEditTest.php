<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserInformationEditTest extends TestCase
{
    use RefreshDatabase;

    /**
     * プロフィール変更画面で初期値が正しく表示されることをテスト.
     */
    public function test_profile_edit_screen_displays_correct_initial_values()
    {
        // テスト用のユーザーを作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'img_url' => 'profile-image.jpg',
            'zip' => '123-4567',
            'address' => '東京都渋谷区テスト住所',
        ]);

        // ユーザーをログイン状態にしてプロフィール変更画面にアクセス
        $response = $this->actingAs($user)->get('/profile/edit');

        // ステータスコード200を確認
        $response->assertStatus(200);

        // 各項目の初期値が正しく表示されていることを確認
        $response->assertSee('テストユーザー');        // ユーザー名
        $response->assertSee('profile-image.jpg');      // プロフィール画像URL
        $response->assertSee('123-4567');               // 郵便番号
        $response->assertSee('東京都渋谷区テスト住所'); // 住所
    }
}
