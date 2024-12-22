<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 小計画面で変更が即時反映されることをテスト
     */
    public function test_payment_method_is_reflected_correctly()
    {
        $user = User::factory()->create();
        Address::factory()->create([
            'user_id' => $user->id,
        ]);
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('/purchase/' . $item->id);
        $response->assertStatus(200);
        $response->assertViewIs('purchase.purchase');

        $paymentMethod = 'card';
        $this->actingAs($user)->post('/purchase/' . $item->id, [
            'payment_method' => $paymentMethod,
        ]);

        $responseContent = $this->actingAs($user)->get('/purchase/' . $item->id)->getContent();
        $this->assertStringContainsString('クレジットカード', $responseContent);
    }
}