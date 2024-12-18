<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 「商品名」で部分一致検索ができることをテスト
     */
    public function test_search_items_by_partial_name()
    {
        Item::factory()->create(['name' => 'Apple iPhone']);
        Item::factory()->create(['name' => 'Apple Watch']);
        Item::factory()->create(['name' => 'Samsung Galaxy']);

        $response = $this->get('/?keyword=Apple');

        $response->assertStatus(200);
        $response->assertViewHas('items', function ($items) {
            return $items->count() === 2 &&
                   $items->first()->name === 'Apple iPhone' &&
                   $items->last()->name === 'Apple Watch';
        });
        $response->assertDontSee('Samsung Galaxy');
    }

    /**
     * 検索状態がマイリストでも保持されていることをテスト
     */
    public function test_search_state_is_retained_on_mylist_page()
    {
        $user = User::factory()->create();
        $favoriteItem1 = Item::factory()->create(['name' => 'Apple iPhone']);
        $user->favoriteItems()->attach($favoriteItem1->id);
        $favoriteItem2 = Item::factory()->create(['name' => 'Samsung Galaxy']);
        $user->favoriteItems()->attach($favoriteItem2->id);

        $response = $this->actingAs($user)->get('/?keyword=Apple');

        $response->assertStatus(200);
        $response->assertSee('Apple iPhone');
        $response->assertDontSee('Samsung Galaxy');

        $this->assertEquals('Apple', session('keyword'));
        $response = $this->actingAs($user)->get('/?page=mylist');

        $response->assertStatus(200);
        $response->assertViewHas('items', function ($items) use ($favoriteItem1, $favoriteItem2) {
            return $items->contains($favoriteItem1) && !$items->contains($favoriteItem2);
        });
    }
}
