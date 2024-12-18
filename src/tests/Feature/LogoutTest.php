<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログアウトできることをテスト
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user);

        $response = $this->post('/logout');
        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
