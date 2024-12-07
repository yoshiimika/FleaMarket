@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/purchase/purchase.css') }}">
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="purchase-container">
    <div class="purchase-details">
        <div class="purchase-item">
            <img src="{{ asset($item->img_url) }}" alt="商品画像" class="purchase-item__image">
            <div class="purchase-item__info">
                <h2 class="purchase-item__name">{{ $item->name }}</h2>
                <p class="purchase-item__price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>
        <hr>
        <div class="purchase-method">
            <h3>支払い方法</h3>
            <form action="{{ route('purchase', $item->id) }}" method="POST">
            @csrf
                <select name="payment_method" class="purchase-method__select" id="payment_method_select">
                    <option value="" disabled selected>選択してください</option>
                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>クレジットカード</option>
                    <option value="convenience" {{ old('payment_method') == 'convenience' ? 'selected' : '' }}>コンビニ払い</option>
                </select>
                <br>
                @error('payment_method')
                    <span class="error__message">{{ $message }}</span>
                @enderror
        </div>
        <hr>
        <div class="purchase-address">
            <div class="purchase-address__header">
                <h3 class="purchase-address__title">配送先</h3>
                <a href="{{ route('address.edit', ['item_id' => $item->id]) }}" class="purchase-address__change-link">変更する</a>
            </div>
            <div class="purchase-address__form">
                    <p class="address-text">〒{{ session('shopping_zip', $address['zip']) }}</p>
                    <p class="address-text">{{ session('shopping_address', $address['address']) }}</p>
                    <p class="address-text">{{ session('shopping_building', $address['building']) }}</p>
            </div>
        </div>
    </div>
    <div class="purchase-summary">
        <div class="summary-box">
            <p class="summary-box__item">商品代金</p>
            <p class="summary-box__price">¥{{ number_format($item->price) }}</p>
        </div>
        <div class="summary-box">
            <p class="summary-box__item">支払い方法</p>
            <p class="summary-box__value" id="selected_payment_method">選択してください</p>
        </div>
        <input type="hidden" name="amount" value="{{ $item->price }}">
            <button class="purchase-summary__button">購入する</button>
            </form>
    </div>
</div>
<script src="{{ asset('js/purchase.js') }}"></script>
@endsection