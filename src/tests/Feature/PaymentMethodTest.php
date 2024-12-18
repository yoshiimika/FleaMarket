<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 支払い方法が正しく選択・反映されることをテスト.
     */
    public function test_payment_method_is_reflected_correctly()
    {
        // ユーザーを作成し、ログイン状態にする
        $user = User::factory()->create();

        // 支払い方法選択画面にアクセス
        $response = $this->actingAs($user)->get('/payment-method');

        // ステータスコード200を確認
        $response->assertStatus(200);

        // 支払い方法を選択するリクエストを送信
        $selectedPaymentMethod = 'credit_card';
        $response = $this->actingAs($user)->post('/payment-method', [
            'payment_method' => $selectedPaymentMethod,
        ]);

        // ステータスコード200を確認
        $response->assertStatus(200);

        // データベースに選択された支払い方法が正しく保存されていることを確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'payment_method' => $selectedPaymentMethod,
        ]);

        // 再度支払い方法画面にアクセスして、選択した支払い方法が反映されていることを確認
        $response = $this->actingAs($user)->get('/payment-method');
        $response->assertSee('credit_card'); // 実際の表示内容に合わせて変更
    }
}
