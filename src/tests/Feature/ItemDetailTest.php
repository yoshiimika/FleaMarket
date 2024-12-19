<?php

namespace Tests\Feature;

use App\Models\Brand;
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
        $user = User::factory()->create();
        $brand = Brand::factory()->create([
            'name' => 'Apple',
        ]);
        $category = $brand->category;
        $item = Item::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'iPhone 13',
            'color' => 'ブラック',
            'description' => '最新のiPhone 13です。',
            'price' => 10000,
            'img_url' => 'https://via.placeholder.com/640x480.png?text=iPhone13',
            'condition' => '良好',
        ]);
        $item->categories()->attach($category->id);
        $user->favoriteItems()->attach($item->id);
        $comment = Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => '値下げ可能ですか？',
        ]);

        $response = $this->actingAs($user)->get('/item/' . $item->id);
        $response->assertStatus(200);
        $response->assertViewIs('item');

        $response->assertSee(htmlspecialchars($item->img_url, ENT_QUOTES, 'UTF-8'));
        $response->assertSee($item->name);
        $response->assertSee($brand->name);
        $response->assertSee('¥' . number_format($item->price));
        $response->assertSee((string) $item->favorites_count);
        $response->assertSee((string) $item->comments_count);
        $response->assertSee($item->description);
        $response->assertSee($item->color);
        $response->assertSee($category->name);
        $response->assertSee($item->condition);
        $response->assertSee($user->name);
        $response->assertSee($comment->content);
    }

    /**
     * 複数選択されたカテゴリが表示されているかをテスト
     */
    public function test_multiple_categories_are_displayed()
    {
        $item = Item::factory()->create(['name' => 'iPhone 13']);
        $category1 = Category::factory()->create(['name' => 'スマートフォン']);
        $category2 = Category::factory()->create(['name' => '電子機器']);
        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);
        $response->assertViewIs('item');

        $response->assertSee('スマートフォン');
        $response->assertSee('電子機器');
    }
}
