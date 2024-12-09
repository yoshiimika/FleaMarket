@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/address.css') }}">
@endsection

@section('content')
<div class="address-container">
    <h1 class="address-title">
        住所の変更
    </h1>
    <form action="{{ route('address.update', ['item_id' => $item_id]) }}" class="address-form" method="POST">
    @csrf
    @method('PUT')
        <div class="address-form__group">
            <label class="address-form__label" for="zip">郵便番号</label>
            <input class="address-form__input" id="zip" name="zip" type="text" value="{{ old('zip', $address->zip ?? '') }}">
            @error('zip')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="address-form__group">
            <label class="address-form__label" for="address">住所</label>
            <input class="address-form__input" id="address" name="address" type="text" value="{{ old('address', $address->address ?? '') }}">
            @error('address')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="address-form__group">
            <label class="address-form__label" for="building">建物名</label>
            <input class="address-form__input" id="building" name="building" type="text" value="{{ old('building', $address->building ?? '') }}">
            @error('building')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="address-form__group">
            <button class="address-form__submit-button" type="submit">
                更新する
            </button>
        </div>
    </form>
</div>
@endsection