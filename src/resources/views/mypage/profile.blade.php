@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/mypage/profile.css') }}">
<div class="profile-container">
    <h1 class="profile-container__title">
        プロフィール設定
    </h1>
    <form action="{{ $isNewProfile ? route('profile.create') : route('profile.update') }}" class="profile-form" enctype="multipart/form-data" method="{{ $isNewProfile ? 'POST' : 'POST' }}">
    @csrf
    @if (!$isNewProfile)
        @method('PUT')
    @endif
        <div class="profile-form__avatar">
            <div class="profile-form__avatar-preview">
                @if ($user->img_url)
                    <img class="profile-form__avatar-img" src="{{ asset('storage/' . $user->img_url) }}">
                @endif
            </div>
            <div class="profile-form__avatar-upload">
                <label class="profile-form__avatar-label" for="avatar">画像を選択する</label>
                <input class="profile-form__avatar-input" id="avatar" name="avatar" type="file">
            </div>
            @error('avatar')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label" for="name">ユーザー名</label>
            <input class="profile-form__input" id="name" name="name" type="text" value="{{ old('name', $user->name) }}">
            @error('name')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>

        <div class="profile-form__group">
            <label class="profile-form__label" for="zip">郵便番号</label>
            <input class="profile-form__input" id="zip" name="zip" type="text" value="{{ old('zip', $user->address->zip) }}">
            @error('zip')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>

        <div class="profile-form__group">
            <label class="profile-form__label" for="address">住所</label>
            <input class="profile-form__input" id="address" name="address" type="text" value="{{ old('address', $user->address->address) }}">
            @error('address')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>

        <div class="profile-form__group">
            <label class="profile-form__label" for="building">建物名</label>
            <input class="profile-form__input" id="building" name="building" type="text" value="{{ old('building', $user->address->building) }}">
            @error('building')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>

        <button class="profile-form__button" type="submit">更新する</button>
    </form>
</div>
@endsection