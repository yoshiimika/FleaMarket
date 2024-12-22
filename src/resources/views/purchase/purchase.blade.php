@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <div class="purchase-details">
        <div class="purchase-item">
            <img alt="{{ $item->name }}の画像" class="purchase-item__image" src="{{ asset($item->img_url) }}">
            <div class="purchase-item__info">
                <h1 class="purchase-item__name">
                    {{ $item->name }}
                </h1>
                <p class="purchase-item__price">
                    ¥{{ number_format($item->price) }}
                </p>
            </div>
        </div>
        <div class="purchase-method">
            <h2 class="purchase-method__title">
                支払い方法
            </h2>
            <form action="{{ route('purchase', $item->id) }}" method="POST">
            @csrf
                <select class="purchase-method__select" id="payment_method_select" name="payment_method">
                    <option value="" disabled selected>選択してください</option>
                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>クレジットカード</option>
                    <option value="convenience" {{ old('payment_method') == 'convenience' ? 'selected' : '' }}>コンビニ払い</option>
                </select>
                <br>
                @error('payment_method')
                    <span class="error__message">{{ $message }}</span>
                @enderror
        </div>
        <div class="purchase-address">
            <div class="purchase-address__header">
                <h2 class="purchase-address__title">
                    配送先
                </h2>
                <a class="purchase-address__change-link" href="{{ route('address.edit', ['item_id' => $item->id]) }}">
                    変更する
                </a>
            </div>
            <div class="purchase-address__form">
                    <p class="address-text">
                        〒{{ session('shopping_zip', $address['zip']) }}
                    </p>
                    <p class="address-text">
                        {{ session('shopping_address', $address['address']) }}
                    </p>
                    <p class="address-text">
                        {{ session('shopping_building', $address['building']) }}
                    </p>
            </div>
        </div>
    </div>
    <div class="purchase-summary">
        <div class="summary-details">
            <div class="summary-box">
                <p class="summary-box__item">
                    商品代金
                </p>
                <p class="summary-box__price">
                    ¥{{ number_format($item->price) }}
                </p>
            </div>
            <div class="summary-box">
                <p class="summary-box__item">
                    支払い方法
                </p>
                <p class="summary-box__value" id="selected_payment_method">
                    選択してください
                </p>
            </div>
        </div>
        <div class="summary-action">
            <input type="hidden" name="amount" value="{{ $item->price }}">
            <button class="purchase-summary__button">
                購入する
            </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/purchase.js') }}"></script>
@endsection