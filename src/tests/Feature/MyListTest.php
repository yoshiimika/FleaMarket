<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * いいねした商品だけが表示されることをテスト
     */
    public function test_only_favorited_items_are_displayed()
    {
        Item::factory()->create();
        $user = User::factory()->create();
        $favoriteItem = Item::factory()->create();
        $user->favoriteItems()->attach($favoriteItem->id);

        $response = $this->actingAs($user)->get('/?page=mylist');

        $response->assertViewHas('items', function ($items) use ($favoriteItem) {
            return $items->contains($favoriteItem) && $items->count() === 1;
        });
    }

    /**
     * 購入済み商品は「SOLD」と表示されることをテスト
     */
    public function test_purchased_items_display_sold_label()
    {
        $user = User::factory()->create();
        $purchasedItem = Item::factory()->create();
        $user->favoriteItems()->attach($purchasedItem->id);
        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
            'amount' => 5000,
            'payment_method' => 'credit_card',
            'shopping_zip' => '123-4567',
            'shopping_address' => '東京都新宿区1-2-3',
        ]);
        $otherFavoriteItem = Item::factory()->create();
        $user->favoriteItems()->attach($otherFavoriteItem->id);

        $response = $this->actingAs($user)->get('/?page=mylist');

        $response->assertSee('SOLD');
        $response->assertDontSee('No items');
    }

    /**
     * 自分が出品した商品は表示されないことをテスト
     */
    public function test_user_items_are_not_displayed_in_mylist()
    {
        $user = User::factory()->create();
        $userItem = Item::factory()->create(['user_id' => $user->id]);
        $otherItem = Item::factory()->create();
        $user->favoriteItems()->attach($otherItem->id);
        Item::factory()->create();

        $response = $this->actingAs($user)->get('/?page=mylist');

        $response->assertViewHas('items', function ($items) use ($otherItem) {
            return $items->contains($otherItem) && $items->count() === 1;
        });
    }

    /**
     * 未認証の場合何も表示されないことをテスト
     */
    public function test_unauthenticated_users_see_nothing()
    {
        $response = $this->get('/?page=mylist');
        $response->assertStatus(200);

        $response->assertViewHas('items', function ($items) {
            return $items->isEmpty();
        });
    }
}