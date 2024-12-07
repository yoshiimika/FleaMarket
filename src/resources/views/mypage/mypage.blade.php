@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/mypage/mypage.css') }}">
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="mypage-container">
    <div class="mypage-header">
        <div class="mypage-header__profile">
            <div class="mypage-header__avatar">
                @if ($user->img_url)
                    <img class="mypage-header__avatar-img" src="{{ asset('storage/' . $user->img_url) }}">
                @endif
            </div>
            <div class="mypage-header__info">
                <h1 class="mypage-header__name">{{ $user->name }}</h1>
            </div>
        </div>
        <div class="mypage-header__button">
            <a class="mypage-header__edit-button" href="{{ route('profile.edit') }}">
                プロフィールを編集
            </a>
        </div>
    </div>
    <div class="mypage-pages">
        <a class="mypage-pages__page {{ $page === 'sell' ? 'mypage-pages__page--active' : '' }}" href="{{ route('profile', ['page' => 'sell']) }}">
            出品した商品
        </a>
        <a class="mypage-pages__page {{ $page === 'buy' ? 'mypage-pages__page--active' : '' }}" href="{{ route('profile', ['page' => 'buy']) }}">
            購入した商品
        </a>
    </div>
    <div class="mypage-items">
        @if($page === 'buy')
            @if($items->isEmpty())
                <p class="no-mypage-items">
                    購入した商品はありません
                </p>
            @else
                @foreach($items as $item)
                    <div class="mypage-item-card">
                        <div class="mypage-item-card__image">
                            <a href="{{ route('item.show', ['item_id' => $item->id]) }}">
                                <img alt="商品画像" class="mypage-item-card__image-img" src="{{ asset($item->img_url) }}">
                            </a>
                            @if ($item->is_sold)
                                <div class="mypage-item-card__label"></div>
                            @endif
                        </div>
                        <div class="mypage-item-card__name">
                            {{ $item->name }}
                        </div>
                    </div>
                @endforeach
            @endif
        @else
            @if($items->isEmpty())
                <p class="no-mypage-items">
                    出品した商品はありません
                </p>
            @else
                @foreach($items as $item)
                    <div class="mypage-item-card">
                        <div class="mypage-item-card__image">
                            <a href="{{ route('item.show', ['item_id' => $item->id]) }}">
                                <img alt="商品画像" class="mypage-item-card__image-img" src="{{ asset($item->img_url) }}">
                            </a>
                            @if ($item->is_sold)
                                <div class="mypage-item-card__label"></div>
                            @endif
                        </div>
                        <div class="mypage-item-card__name">
                            {{ $item->name }}
                        </div>
                    </div>
                @endforeach
            @endif
        @endif
    </div>
</div>
@endsection