<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemPurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 「購入する」ボタンを押下すると購入が完了することをテスト
     */
    public function test_user_can_purchase_a_item()
    {
        $user = User::factory()->create();
        Address::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('/purchase/' . $item->id);
        $response->assertStatus(200);
        $response->assertViewIs('purchase.purchase');

        $this->actingAs($user)->post('/purchase/' . $item->id, [
            'payment_method' => 'card',
        ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
        $this->assertTrue($item->refresh()->is_sold);
    }

    /**
     * 購入した商品は商品一覧画面にて「SOLD」と表示されることをテスト
     */
    public function test_purchased_item_displays_sold_in_item_list()
    {
        $user = User::factory()->create();
        Address::factory()->create(['user_id' => $user->id]);
        $purchasableItem = Item::factory()->create();
        $unpurchasedItem = Item::factory()->create();

        $response = $this->actingAs($user)->get('/purchase/' . $purchasableItem->id);
        $response->assertStatus(200);
        $response->assertViewIs('purchase.purchase');

        $this->actingAs($user)->post('/purchase/' . $purchasableItem->id, [
            'payment_method' => 'card',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('index');

        $response->assertSee($purchasableItem->name);
        $response->assertSee('SOLD');

        $response->assertSee($unpurchasedItem->name);
        $response->assertDontSee($unpurchasedItem->name . 'SOLD');
    }

    /**
     * 「プロフィール/購入した商品一覧」に追加されていることをテスト
     */
    public function test_purchased_item_is_added_to_user_profile()
    {
        $user = User::factory()->create();
        Address::factory()->create(['user_id' => $user->id]);
        $purchasableItem = Item::factory()->create();

        $response = $this->actingAs($user)->get('/purchase/' . $purchasableItem->id);
        $response->assertStatus(200);
        $response->assertViewIs('purchase.purchase');

        $this->actingAs($user)->post('/purchase/' . $purchasableItem->id, [
            'payment_method' => 'card',
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertViewIs('mypage.mypage');

        $response->assertSee($purchasableItem->name);
    }
}