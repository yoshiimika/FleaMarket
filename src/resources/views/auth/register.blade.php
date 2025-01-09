@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register-container">
    <h1 class="register-container__title">
        会員登録
    </h1>
    <form action="{{ route('register') }}" class="register-form" method="POST">
    @csrf
        <div class="register-form__group">
            <label class="register-form__label" for="name">ユーザー名</label>
            <input class="register-form__input" id="name" name="name" type="text" value="{{old('name')}}">
        </div>
        <div class="error__group">
            @error('name')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="register-form__group">
            <label class="register-form__label" for="email">メールアドレス</label>
            <input class="register-form__input" id="email" name="email" type="text" value="{{old('email')}}">
        </div>
        <div class="error__group">
            @error('email')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="register-form__group">
            <label class="register-form__label" for="password">パスワード</label>
            <input class="register-form__input" id="password" name="password" type="password">
        </div>
        <div class="error__group">
            @error('password')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="register-form__group">
            <label class="register-form__label" for="password_confirmation">確認用パスワード</label>
            <input class="register-form__input" id="password_confirmation" name="password_confirmation" type="password">
        </div>
        <button type="submit" class="register-form__button">
            登録する
        </button>
        <div class="register-form__login-link">
            <a class="register-form__login-link-text" href="{{ route('login') }}">ログインはこちら</a>
        </div>
    </form>
</div>
@endsection