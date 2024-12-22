<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログイン済みのユーザーはコメントを送信できることをテスト
     */
    public function test_authenticated_user_can_send_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'これはテストコメントです。',
        ]);
        $this->assertEquals(0, $item->comments_count);

        $this->actingAs($user)->post('/item/' . $item->id . '/comment', [
            'content' => 'これはテストコメントです。',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'これはテストコメントです。',
        ]);
        $this->assertEquals(1, $item->refresh()->comments_count);
    }

    /**
     * ログイン前のユーザーはコメントを送信できないことをテスト
     */
    public function test_guest_cannot_send_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post('/item/' . $item->id . '/comment', [
            'content' => '未認証ユーザーのコメント',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'content' => '未認証ユーザーのコメント',
        ]);
    }

    /**
     * コメントが入力されていない場合、バリデーションメッセージが表示されることをテスト
     */
    public function test_comment_requires_content()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post('/item/' . $item->id . '/comment', [
            'content' => '',
        ]);

        $response->assertSessionHasErrors(['content' => 'コメントを入力してください']);
    }

    /**
     * コメントが255文字以上の場合、バリデーションメッセージが表示されることをテスト
     */
    public function test_comment_must_not_exceed_255_characters()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $longComment = str_repeat('あ', 256);
        $response = $this->actingAs($user)->post('/item/' . $item->id . '/comment', [
            'content' => $longComment,
        ]);

        $response->assertSessionHasErrors(['content' => 'コメントは255文字以内で入力してください']);
    }
}