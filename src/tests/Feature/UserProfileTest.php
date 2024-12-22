<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 必要な情報が取得できることをテスト（出品した商品一覧）
     */
    public function test_profile_page_shows_listed_items()
    {
        $user = User::factory()->create();
        $listedItems = Item::factory(5)->sequence(
            ['name' => 'Sell Item 1'],
            ['name' => 'Sell Item 2'],
            ['name' => 'Sell Item 3'],
            ['name' => 'Sell Item 4'],
            ['name' => 'Sell Item 5'],
        )->create([
            'user_id' => $user->id,
        ]);
        $purchasedItems = Item::factory(5)->sequence(
            ['name' => 'Buy Item 1'],
            ['name' => 'Buy Item 2'],
            ['name' => 'Buy Item 3'],
            ['name' => 'Buy Item 4'],
            ['name' => 'Buy Item 5'],
        )->create();
        foreach ($purchasedItems as $purchasedItem) {
            Purchase::factory()->create([
                'user_id' => $user->id,
                'item_id' => $purchasedItem->id,
            ]);
        }

        $response = $this->actingAs($user)->get('/mypage?page=sell');
        $response->assertStatus(200);
        $response->assertViewIs('mypage.mypage');

        $response->assertSee(htmlspecialchars($user->img_url, ENT_QUOTES, 'UTF-8'));
        $response->assertSee($user->name);
        foreach ($listedItems as $listedItem) {
            $response->assertSee($listedItem->name);
        }
        foreach ($purchasedItems as $purchasedItem) {
            $response->assertDontSee($purchasedItem->name);
        }
    }

    /**
     * 必要な情報が取得できることをテスト（購入した商品一覧）
     */
    public function test_profile_page_shows_purchased_items()
    {
        $user = User::factory()->create();
        $listedItems = Item::factory(5)->sequence(
            ['name' => 'Sell Item 1'],
            ['name' => 'Sell Item 2'],
            ['name' => 'Sell Item 3'],
            ['name' => 'Sell Item 4'],
            ['name' => 'Sell Item 5'],
        )->create([
            'user_id' => $user->id,
        ]);
        $purchasedItems = Item::factory(5)->sequence(
            ['name' => 'Buy Item 1'],
            ['name' => 'Buy Item 2'],
            ['name' => 'Buy Item 3'],
            ['name' => 'Buy Item 4'],
            ['name' => 'Buy Item 5'],
        )->create();
        foreach ($purchasedItems as $purchasedItem) {
            Purchase::factory()->create([
                'user_id' => $user->id,
                'item_id' => $purchasedItem->id,
            ]);
        }

        $response = $this->actingAs($user)->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertViewIs('mypage.mypage');

        $response->assertSee(htmlspecialchars($user->img_url, ENT_QUOTES, 'UTF-8'));
        $response->assertSee($user->name);
        foreach ($listedItems as $listedItem) {
            $response->assertDontSee($listedItem->name);
        }
        foreach ($purchasedItems as $purchasedItem) {
            $response->assertSee($purchasedItem->name);
        }
    }
}
