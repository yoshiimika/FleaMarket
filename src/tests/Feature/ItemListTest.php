<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 全商品を取得できることをテスト
     */
    public function test_all_items_are_displayed()
    {
        Item::factory()->count(5)->create();
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewHas('items', function ($items) {
            return $items->count() === 5;
        });
    }

    /**
     * 購入済み商品には「SOLD」と表示されることをテスト
     */
    public function test_purchased_items_display_sold_label()
    {
        $user = User::factory()->create();
        $purchasedItem = Item::factory()->create();
        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
            'amount' => 5000,
            'payment_method' => 'credit_card',
            'shopping_zip' => '123-4567',
            'shopping_address' => '東京都新宿区1-2-3',
        ]);

        $response = $this->get('/');

        $response->assertSee('SOLD');
    }

    /**
     * 自分が出品した商品が表示されないことをテスト
     */
    public function test_user_items_are_not_displayed_in_list()
    {
        $user = User::factory()->create();
        $userItem = Item::factory()->create(['user_id' => $user->id]);
        $otherItem = Item::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertViewHas('items', function ($items) use ($otherItem) {
            return $items->contains($otherItem) && $items->count() === 1;
        });
    }
}
