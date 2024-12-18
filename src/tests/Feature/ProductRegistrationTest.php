<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品出品画面で必要な情報が正しく保存されることをテスト.
     */
    public function test_product_registration_saves_correct_information()
    {
        // ユーザーを作成しログイン状態にする
        $user = User::factory()->create();

        // カテゴリを作成
        $category = Category::factory()->create(['name' => '家電']);

        // 出品情報データ
        $productData = [
            'category_id' => $category->id,
            'condition' => '新品',
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 10000,
        ];

        // 商品出品画面からPOSTリクエストを送信
        $response = $this->actingAs($user)->post('/products', $productData);

        // ステータスコード302（リダイレクト）を確認
        $response->assertStatus(302);

        // データベースに商品情報が保存されていることを確認
        $this->assertDatabaseHas('products', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'condition' => '新品',
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 10000,
        ]);
    }
}
