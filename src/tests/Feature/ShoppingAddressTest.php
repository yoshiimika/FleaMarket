<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingAddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 送付先住所変更画面で登録した住所が商品購入画面に反映されることをテスト.
     */
    public function test_shipping_address_is_reflected_on_purchase_screen()
    {
        // ユーザーを作成しログイン状態にする
        $user = User::factory()->create([
            'address' => '初期住所',
        ]);

        // 送付先住所を変更
        $updatedAddress = '東京都渋谷区新しい住所123';
        $response = $this->actingAs($user)->post('/shipping-address/update', [
            'address' => $updatedAddress,
        ]);

        // 住所変更が成功することを確認
        $response->assertStatus(302); // リダイレクト確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'address' => $updatedAddress,
        ]);

        // 商品購入画面にアクセスして変更された住所が表示されることを確認
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->get('/products/' . $product->id . '/purchase');

        $response->assertSee($updatedAddress);
    }

    /**
     * 購入した商品に送付先住所が紐づいて登録されることをテスト.
     */
    public function test_shipping_address_is_saved_with_purchased_product()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create([
            'address' => '東京都渋谷区テスト住所456',
        ]);
        $product = Product::factory()->create();

        // 商品を購入するリクエストを送信
        $response = $this->actingAs($user)->post('/products/' . $product->id . '/purchase');

        // 購入データがデータベースに保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'shipping_address' => '東京都渋谷区テスト住所456',
        ]);

        // ステータスコード200を確認
        $response->assertStatus(200);
    }
}
