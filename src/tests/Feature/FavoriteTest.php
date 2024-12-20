<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * いいねアイコンを押下することによって、いいねした商品として登録することができることをテスト
     */
    public function test_user_can_like_a_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('/item/' . $item->id);
        $response->assertStatus(200);
        $response->assertViewIs('item');

        $this->assertEquals(0, $item->favorites_count);

        $response = $this->actingAs($user)
            ->followingRedirects()
            ->post('/item/' . $item->id . '/favorite');

        $this->assertEquals(1, $item->refresh()->favorites_count);
        $this->assertTrue($item->favoriteByUsers()->where('user_id', $user->id)->exists());
    }

    /**
     * 追加済みのアイコンは色が変化することをテスト
     */
    public function test_liked_item_icon_changes_color()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('/item/' . $item->id);
        $response->assertStatus(200);
        $response->assertViewIs('item');

        $responseContent = $response->getContent();
        $this->assertStringContainsString('class="favorite-icon"', $responseContent);
        $this->assertStringNotContainsString('class="favorite-icon active"', $responseContent);

        $response = $this->actingAs($user)
            ->followingRedirects()
            ->post('/item/' . $item->id . '/favorite');

        $responseContent = $response->getContent();
        $this->assertStringContainsString('class="favorite-icon active"', $responseContent);
        $this->assertStringNotContainsString('class="favorite-icon"', $responseContent);
    }

    /**
     * 再度いいねアイコンを押下することによって、いいねを解除することができることをテスト
     */
    public function test_user_can_unlike_a_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $user->favoriteItems()->attach($item->id);

        $response = $this->actingAs($user)->get('/item/' . $item->id);
        $response->assertStatus(200);
        $response->assertViewIs('item');

        $this->assertEquals(1, $item->refresh()->favorites_count);
        $this->assertTrue($item->favoriteByUsers()->where('user_id', $user->id)->exists());

        $response = $this->actingAs($user)
            ->followingRedirects()
            ->post('/item/' . $item->id . '/favorite');

        $this->assertEquals(0, $item->refresh()->favorites_count);
        $this->assertFalse($item->favoriteByUsers()->where('user_id', $user->id)->exists());
    }
}
