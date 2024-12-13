@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="login-container">
    <h1 class="login-container__title">
        ログイン
    </h1>
    <form action="{{ route('login') }}" class="login-form" method="POST">
    @csrf
        <div class="login-form__group">
            <label class="login-form__label" for="email">ユーザー名 / メールアドレス</label>
            <input class="login-form__input" id="email" name="email" type="text" value="{{old('email')}}">
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
            <label class="login-form__label" for="password">パスワード</label>
            <input class="login-form__input" id="password" name="password" type="password">
        </div>
        <div class="error__group">
            @error('password')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="login-form__button">
            ログインする
        </button>
        <div class="login-form__register-link">
            <a class="login-form__register-link-text" href="{{ route('register') }}">
                会員登録はこちら
            </a>
        </div>
    </form>
</div>
@endsection