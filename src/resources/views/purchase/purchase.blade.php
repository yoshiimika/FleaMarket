@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/purchase/purchase.css') }}">

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
            <select name="payment_method" class="purchase-method__select">
                <option value="" disabled selected>選択してください</option>
                <option value="credit_card">クレジットカード</option>
                <option value="convenience_store">コンビニ払い</option>
                <option value="bank_transfer">銀行振込</option>
            </select>
        </div>

        <hr>

        <div class="purchase-address">
            <h3>配送先</h3>
            <p>〒 XXX-YYYY</p>
            <p>ここには住所と建物が入ります</p>
            <a href="#" class="purchase-address__change-link">変更する</a>
        </div>
    </div>

    <div class="purchase-summary">
        <div class="summary-box">
            <p class="summary-box__item">商品代金</p>
            <p class="summary-box__price">¥{{ number_format($item->price) }}</p>
        </div>
        <div class="summary-box">
            <p class="summary-box__item">支払い方法</p>
            <p class="summary-box__value">コンビニ払い</p>
        </div>
        <button class="purchase-summary__button">購入する</button>
    </div>
</div>
@endsection