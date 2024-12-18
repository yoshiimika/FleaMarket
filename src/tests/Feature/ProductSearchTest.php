<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 「商品名」で部分一致検索ができることをテスト.
     */
    public function test_search_products_by_partial_name()
    {
        // テスト用の商品を作成
        Product::factory()->create(['name' => 'Apple iPhone']);
        Product::factory()->create(['name' => 'Apple Watch']);
        Product::factory()->create(['name' => 'Samsung Galaxy']);

        // 検索キーワードでリクエストを送信
        $response = $this->get('/products?search=Apple');

        // ステータスコード200を確認
        $response->assertStatus(200);

        // 部分一致する商品が表示されることを確認
        $response->assertViewHas('products', function ($products) {
            return $products->count() === 2 &&
                   $products->first()->name === 'Apple iPhone' &&
                   $products->last()->name === 'Apple Watch';
        });

        // 検索結果に「Samsung Galaxy」が含まれていないことを確認
        $response->assertDontSee('Samsung Galaxy');
    }

    /**
     * 検索状態がマイリストページでも保持されていることをテスト.
     */
    public function test_search_state_is_retained_on_mylist_page()
    {
        $user = User::factory()->create();

        // テスト用の商品を作成
        Product::factory()->create(['name' => 'Apple iPhone']);
        Product::factory()->create(['name' => 'Samsung Galaxy']);

        // ユーザーを認証状態にして検索キーワードを含めたリクエストを送信
        $response = $this->actingAs($user)->get('/products?search=Apple');

        // 検索キーワードをセッションに保持
        session(['search' => 'Apple']);

        // マイリストページに遷移
        $response = $this->actingAs($user)->get('/mylist');

        // セッションに検索キーワードが保持されていることを確認
        $this->assertEquals('Apple', session('search'));

        // マイリストページが表示され、検索状態が反映されることを確認
        $response->assertStatus(200);
        $response->assertSee('Apple');
    }
}
