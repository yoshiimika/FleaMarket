<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログイン済みユーザーがコメントを送信できることをテスト.
     */
    public function test_authenticated_user_can_send_comment()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $product = Product::factory()->create();

        // ユーザーをログイン状態にし、コメントを送信
        $response = $this->actingAs($user)->post('/products/' . $product->id . '/comments', [
            'content' => 'これはテストコメントです。',
        ]);

        // ステータスコード200を確認
        $response->assertStatus(200);

        // コメントがデータベースに保存されていることを確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'content' => 'これはテストコメントです。',
        ]);
    }

    /**
     * ログインしていないユーザーはコメントを送信できないことをテスト.
     */
    public function test_guest_cannot_send_comment()
    {
        $product = Product::factory()->create();

        // 未認証状態でコメント送信リクエストを送信
        $response = $this->post('/products/' . $product->id . '/comments', [
            'content' => '未認証ユーザーのコメント',
        ]);

        // 未認証ユーザーはログインページにリダイレクトされることを確認
        $response->assertRedirect('/login');
    }

    /**
     * コメントが未入力の場合、バリデーションメッセージが表示されることをテスト.
     */
    public function test_comment_requires_content()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        // 空のコメント内容を送信
        $response = $this->actingAs($user)->post('/products/' . $product->id . '/comments', [
            'content' => '',
        ]);

        // バリデーションエラーが返されることを確認
        $response->assertSessionHasErrors(['content' => 'コメントを入力してください']);
    }

    /**
     * コメントが255文字以上の場合、バリデーションメッセージが表示されることをテスト.
     */
    public function test_comment_must_not_exceed_255_characters()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        // 256文字のコメントを送信
        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($user)->post('/products/' . $product->id . '/comments', [
            'content' => $longComment,
        ]);

        // バリデーションエラーが返されることを確認
        $response->assertSessionHasErrors(['content' => 'コメントは255文字以内で入力してください']);
    }
}
