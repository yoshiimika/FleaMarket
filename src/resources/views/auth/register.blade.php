@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register-container">
    <h1 class="register-container__title">会員登録</h1>

    <form method="POST" action="{{ route('register') }}" class="register-form">
        @csrf
        <div class="register-form__group">
            <label for="name" class="register-form__label">ユーザー名</label>
            <input type="text" id="name" name="name" class="register-form__input" value="{{old('name')}}">
        </div>
        <div class="error__group">
            @error('name')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="register-form__group">
            <label for="email" class="register-form__label">メールアドレス</label>
            <input type="email" id="email" name="email" class="register-form__input" value="{{old('email')}}">
        </div>
        <div class="error__group">
            @error('email')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="register-form__group">
            <label for="password" class="register-form__label">パスワード</label>
            <input type="password" id="password" name="password" class="register-form__input">
        </div>
        <div class="error__group">
            @error('password')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="register-form__group">
            <label for="password_confirmation" class="register-form__label">確認用パスワード</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="register-form__input">
        </div>
        <button type="submit" class="register-form__button">登録する</button>
        <div class="register-form__login-link">
            <a href="{{ route('login') }}" class="register-form__login-link-text">ログインはこちら</a>
        </div>
    </form>
</div>
@endsection