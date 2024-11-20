@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/mylist.css') }}">
<div class="container">
    <div class="tabs">
        <a class="tabs__tab" href="{{ route('home') }}">
            おすすめ
        </a>
        <a class="tabs__tab--active" href="{{ route('mylist') }}">
            マイリスト
        </a>
    </div>
    <div class="items">
    @if(auth()->check())
        @if($favoriteItems->isEmpty())
            <p class="no-items">マイリストに商品がありません。</p>
        @else
            <div class="items">
                @foreach($favoriteItems as $item)
                    <div class="item-card">
                        <div class="item-card__image">
                            <img alt="商品画像" class="item-card__image-img" src="{{ asset($item->img_url) }}">
                        </div>
                        <div class="item-card__name">
                            {{ $item->name }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <p class="not-authenticated">マイリストを表示するにはログインしてください。</p>
    @endif
    </div>
</div>
@endsection