<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 「購入する」ボタンを押下すると購入が完了することをテスト.
     */
    public function test_user_can_purchase_a_product()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $product = Product::factory()->create(['is_sold' => false]);

        // ユーザーをログイン状態にし、購入リクエストを送信
        $response = $this->actingAs($user)->post('/products/' . $product->id . '/purchase');

        // ステータスコード200を確認
        $response->assertStatus(200);

        // 購入データがデータベースに保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // 商品の「is_sold」状態がtrueになっていることを確認
        $product->refresh();
        $this->assertTrue($product->is_sold);
    }

    /**
     * 購入した商品が商品一覧画面にて「sold」と表示されることをテスト.
     */
    public function test_purchased_product_displays_sold_in_product_list()
    {
        // ユーザーと商品を作成し、商品を購入状態にする
        $user = User::factory()->create();
        $product = Product::factory()->create(['is_sold' => true]);

        // 商品一覧ページにアクセス
        $response = $this->actingAs($user)->get('/products');

        // 商品が「sold」として表示されていることを確認
        $response->assertSee('sold');
    }

    /**
     * 購入した商品がプロフィールの「購入した商品一覧」に追加されることをテスト.
     */
    public function test_purchased_product_is_added_to_user_profile()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $product = Product::factory()->create(['is_sold' => false]);

        // 購入データを作成
        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // プロフィール画面にアクセス
        $response = $this->actingAs($user)->get('/profile');

        // 購入した商品が表示されていることを確認
        $response->assertSee($product->name);
    }
}