<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * プロフィールページで必要な情報が正しく表示されることをテスト.
     */
    public function test_user_profile_displays_correct_information()
    {
        // テスト用のユーザーを作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'img_url' => 'profile-image.jpg',
        ]);

        // 出品した商品を作成
        $products = Product::factory(2)->create([
            'user_id' => $user->id,
            'name' => '出品商品1',
        ]);

        // 購入した商品を作成
        $purchasedProduct = Product::factory()->create(['name' => '購入商品1']);
        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $purchasedProduct->id,
        ]);

        // ユーザーをログイン状態にしてプロフィールページへアクセス
        $response = $this->actingAs($user)->get('/profile');

        // ステータスコード200を確認
        $response->assertStatus(200);

        // ユーザー情報が表示されていることを確認
        $response->assertSee('テストユーザー');
        $response->assertSee('profile-image.jpg'); // 画像URL確認

        // 出品した商品が表示されていることを確認
        $response->assertSee('出品商品1');

        // 購入した商品が表示されていることを確認
        $response->assertSee('購入商品1');
    }
}
