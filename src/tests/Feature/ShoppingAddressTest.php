<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingAddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 送付先住所変更画面にて登録した住所が商品購入画面に反映されていることをテスト
     */
    public function test_shipping_address_is_reflected_on_purchase_screen()
    {
        $user = User::factory()->create();
        Address::factory()->create([
            'user_id' => $user->id,
            'zip' => '100-0001',
            'address' => '東京都千代田区既存の住所1-1-1',
            'building' => '既存のビル'
        ]);
        $item = Item::factory()->create();

        $this->actingAs($user)->put('/purchase/address/'. $item->id, [
            'zip' => '150-0002',
            'address' => '東京都渋谷区新しい住所123',
            'building' => '新しいビル'
        ]);

        $response = $this->actingAs($user)->get('/purchase/' . $item->id);
        $response->assertStatus(200);
        $response->assertViewIs('purchase.purchase');

        $response->assertSee('150-0002');
        $response->assertSee('東京都渋谷区新しい住所123');
        $response->assertSee('新しいビル');
    }

    /**
     * 購入した商品に送付先住所が紐づいて登録されることをテスト
     */
    public function test_shipping_address_is_saved_with_purchased_item()
    {
        $user = User::factory()->create();
        Address::factory()->create([
            'user_id' => $user->id,
            'zip' => '100-0001',
            'address' => '東京都千代田区既存の住所1-1-1',
            'building' => '既存のマンション'
        ]);
        $item = Item::factory()->create();

        $this->actingAs($user)->put('/purchase/address/'. $item->id, [
            'zip' => '150-0002',
            'address' => '東京都渋谷区新しい住所123',
            'building' => '新しいマンション'
        ]);

        $this->actingAs($user)->post('/purchase/' . $item->id, [
            'payment_method' => 'card',
        ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shopping_address' => '150-0002',
            'shopping_address' => '東京都渋谷区新しい住所123',
            'shopping_building' => '新しいマンション'
        ]);
    }
}