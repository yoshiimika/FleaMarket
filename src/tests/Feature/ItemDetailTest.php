<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 必要な情報が表示されることをテスト
     */
    public function test_item_details_are_displayed()
    {
        // テスト用のユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'name' => 'iPhone 13',
            'brand_name' => 'Apple',
            'price' => 120000,
            'description' => '最新のiPhone 13です。',
            'condition' => '新品',
            'likes_count' => 5,
        ]);

        // コメントを追加
        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => '素晴らしい商品です！',
        ]);

        // 商品詳細ページにアクセス
        $response = $this->get('/{item_id}' . $item->id);

        // ステータスコード200を確認
        $response->assertStatus(200);

        // 商品詳細情報が正しく表示されることを確認
        $response->assertSee('iPhone 13');
        $response->assertSee('Apple');
        $response->assertSee('120000');
        $response->assertSee('5'); // いいね数
        $response->assertSee('最新のiPhone 13です。');
        $response->assertSee('新品');
        $response->assertSee('素晴らしい商品です！'); // コメント内容
    }

    /**
     * 複数選択されたカテゴリが表示されているかをテスト
     */
    public function test_multiple_categories_are_displayed()
    {
        // 商品とカテゴリを作成
        $item = Item::factory()->create(['name' => 'Galaxy S22']);
        $category1 = Category::factory()->create(['name' => 'スマートフォン']);
        $category2 = Category::factory()->create(['name' => 'Android']);

        // 商品にカテゴリを関連付け
        $item->categories()->attach([$category1->id, $category2->id]);

        // 商品詳細ページにアクセス
        $response = $this->get('/{item_id}' . $item->id);

        // ステータスコード200を確認
        $response->assertStatus(200);

        // 複数カテゴリが表示されることを確認
        $response->assertSee('スマートフォン');
        $response->assertSee('Android');
    }
}
