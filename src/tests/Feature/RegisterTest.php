<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 名前が入力されていない場合、バリデーションメッセージが表示されることをテスト
     */
    public function test_name_is_required()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');

        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    /**
     * メールアドレスが入力されていない場合、バリデーションメッセージが表示されることをテスト
     */
    public function test_email_is_required()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');

        $response = $this->post('/register', [
            'name' => '山田 太郎',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    /**
     * パスワードが入力されていない場合、バリデーションメッセージが表示されることをテスト
     */
    public function test_password_is_required()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');

        $response = $this->post('/register', [
            'name' => '山田 太郎',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    /**
     * パスワードが7文字以下の場合、バリデーションメッセージが表示されることをテスト
     */
    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');

        $response = $this->post('/register', [
            'name' => '山田 太郎',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    /**
     * パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示されることをテスト
     */
    public function test_password_confirmation_must_match()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');

        $response = $this->post('/register', [
            'name' => '山田 太郎',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードと一致しません']);
    }

    /**
     * 全ての項目が入力されている場合、会員情報が登録され、ログイン画面に遷移されることをテスト
     */
    public function test_registration_successful()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');

        $response = $this->post('/register', [
            'name' => '山田 太郎',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('users', ['email' => 'test@example.com',]);
    }
}
