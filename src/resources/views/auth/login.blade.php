@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">

<div class="login-container">
    <h1 class="login-container__title">ログイン</h1>

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf
        <div class="login-form__group">
            <label for="email" class="login-form__label">ユーザー名 / メールアドレス</label>
            <input type="text" id="email" name="email" class="login-form__input" value="{{old('email')}}">
        </div>
        <div class="error__group">
            @error('email')
                <span class="error__message">{{ $message }}</span>
            @enderror
            @error('auth.failed')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="login-form__group">
            <label for="password" class="login-form__label">パスワード</label>
            <input type="password" id="password" name="password" class="login-form__input">
        </div>
        <div class="error__group">
            @error('password')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="login-form__button">ログインする</button>

        <div class="login-form__register-link">
            <a href="{{ route('register') }}" class="login-form__register-link-text">会員登録はこちら</a>
        </div>
    </form>
</div>
@endsection