<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserInformationEditTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 変更項目が初期値として過去設定されていることをテスト
     */
    public function test_profile_edit_screen_displays_correct_initial_values()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $avatar = UploadedFile::fake()->image('profile-image.jpg');
        $this->actingAs($user)->put('mypage/profile', [
            'avatar' => $avatar,
            'name' => '山田 太郎',
            'zip' => '100-0001',
            'address' => '東京都千代田区テスト住所1-1-1',
            'building' => 'テストビル',
        ]);
        $storedFilePath = 'avatars/' . $avatar->hashName();

        $response = $this->actingAs($user)->get('mypage/profile');
        $response->assertStatus(200);
        $response->assertViewIs('mypage.profile');

        $response->assertSee(asset('storage/' . $storedFilePath));
        $response->assertSee('山田 太郎');
        $response->assertSee('100-0001');
        $response->assertSee('東京都千代田区テスト住所1-1-1');
        $response->assertSee('テストビル');
        Storage::disk('public')->assertExists($storedFilePath);
    }
}
