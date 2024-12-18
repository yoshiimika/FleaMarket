<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * いいねアイコンを押すと、商品がいいねとして登録されることをテスト.
     */
    public function test_user_can_like_a_product()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $product = Product::factory()->create();

        // ユーザーをログイン状態にして、いいねリクエストを送信
        $response = $this->actingAs($user)->post('/products/' . $product->id . '/favorite');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // データベースにいいね情報が登録されていることを確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // いいね合計値が増加することを確認
        $this->assertEquals(1, $product->favorites()->count());
    }

    /**
     * いいね済みのアイコンが状態変化することをテスト.
     */
    public function test_liked_product_icon_changes_color()
    {
        // ユーザーと商品を作成し、いいね状態にする
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $product->favorites()->attach($user->id); // いいね登録

        // 商品詳細ページにアクセス
        $response = $this->actingAs($user)->get('/products/' . $product->id);

        // いいね済みのアイコンが表示されていることを確認
        $response->assertSee('class="icon--liked"'); // 実際のHTMLクラス名に合わせて修正
    }

    /**
     * いいねアイコンを再度押すと、いいねが解除されることをテスト.
     */
    public function test_user_can_unlike_a_product()
    {
        // ユーザーと商品を作成し、いいね状態にする
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $product->favorites()->attach($user->id); // いいね登録

        // ユーザーをログイン状態にして、いいね解除リクエストを送信
        $response = $this->actingAs($user)->delete('/products/' . $product->id . '/favorite');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // データベースからいいね情報が削除されていることを確認
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // いいね合計値が減少することを確認
        $this->assertEquals(0, $product->favorites()->count());
    }
}
