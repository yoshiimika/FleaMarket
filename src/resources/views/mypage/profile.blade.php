@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/mypage/profile.css') }}">

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="profile-container">
    <h1 class="profile-container__title">
        プロフィール設定
    </h1>
    <form action="{{ route('profile.update') }}" class="profile-form" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PUT')
        <div class="profile-form__avatar">
            <img src="{{ $user->img_url ?? asset('img/default-avatar.png') }}" class="profile-form__avatar-img">
            <label class="profile-form__avatar-label" for="avatar">画像を選択する</label>
            <input class="profile-form__avatar-input" id="avatar" name="avatar" type="file">
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label" for="name">ユーザー名</label>
            <input class="profile-form__input" id="name" name="name" type="text" value="{{ $user->name }}">
        </div>

        <div class="profile-form__group">
            <label class="profile-form__label" for="zip">郵便番号</label>
            <input class="profile-form__input" id="zip" name="zip" type="text" value="{{ $user->zip }}">
        </div>

        <div class="profile-form__group">
            <label class="profile-form__label" for="address">住所</label>
            <input class="profile-form__input" id="address" name="address" type="text" value="{{ $user->address }}">
        </div>

        <div class="profile-form__group">
            <label class="profile-form__label" for="building">建物名</label>
            <input class="profile-form__input" id="building" name="building" type="text" value="{{ $user->building }}">
        </div>

        <button class="profile-form__button" type="submit">更新する</button>
    </form>
</div>
@endsection