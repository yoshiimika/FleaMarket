<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 必要な情報が表示されることをテスト
     */
    public function test_item_details_are_displayed()
    {
        Storage::fake('public');

        $avatar = UploadedFile::fake()->image('profile-image.jpg')->storeAs('avatars', 'profile-image.jpg', 'public');
        $user = User::factory()->create([
            'name' => '山田 太郎',
            'img_url' => $avatar,
        ]);
        $categories = Category::factory(2)->sequence(
            ['name' => 'スマートフォン'],
            ['name' => '電子機器']
        )->create();
        $brand = Brand::factory()->create([
            'category_id' => $categories->first()->id,
            'name' => 'Apple',
        ]);
        $item = Item::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'iPhone 13',
            'color' => 'ブラック',
            'description' => '最新のiPhone 13です。',
            'price' => 10000,
            'img_url' => 'https://via.placeholder.com/640x480.png?text=iPhone13',
            'condition' => '良好',
        ]);
        $item->categories()->attach($categories->pluck('id')->toArray());
        $user->favoriteItems()->attach($item->id);
        $comment = Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => '値下げ可能ですか？',
        ]);

        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);
        $response->assertViewIs('item');

        $response->assertSee('https://via.placeholder.com/640x480.png?text=iPhone13');
        $response->assertSee('iPhone 13');
        $response->assertSee('Apple');
        $response->assertSee('¥' . number_format('10000'));
        $response->assertSee($item->favorites_count);
        $response->assertSee($item->comments_count);
        $response->assertSee('ブラック');
        $response->assertSee('最新のiPhone 13です。');
        $response->assertSee('スマートフォン');
        $response->assertSee('電子機器');
        $response->assertSee('良好');
        $response->assertSee(asset('storage/' . $avatar));
        $response->assertSee('山田 太郎');
        $response->assertSee('値下げ可能ですか？');
    }

    /**
     * 複数選択されたカテゴリが表示されているかをテスト
     */
    public function test_multiple_categories_are_displayed()
    {
        $categories = Category::factory(2)->sequence(
            ['name' => 'スマートフォン'],
            ['name' => '電子機器']
        )->create();
        $item = Item::factory()->create([
            'name' => 'iPhone 13'
        ]);
        $item->categories()->attach($categories->pluck('id')->toArray());

        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);
        $response->assertViewIs('item');

        $response->assertSee('スマートフォン');
        $response->assertSee('電子機器');
    }
}
