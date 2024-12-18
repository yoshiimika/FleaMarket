<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品出品画面で必要な情報が正しく保存されることをテスト.
     */
    public function test_item_registration_saves_correct_information()
    {
        // ユーザーを作成しログイン状態にする
        $user = User::factory()->create();

        // カテゴリを作成
        $category = Category::factory()->create(['name' => '家電']);

        // 出品情報データ
        $itemData = [
            'category_id' => $category->id,
            'condition' => '新品',
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 10000,
        ];

        // 商品出品画面からPOSTリクエストを送信
        $response = $this->actingAs($user)->post('/items', $itemData);

        // ステータスコード302（リダイレクト）を確認
        $response->assertStatus(302);

        // データベースに商品情報が保存されていることを確認
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'condition' => '新品',
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 10000,
        ]);
    }
}
