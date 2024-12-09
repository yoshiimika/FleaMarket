@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="container">
    <div class="pages">
        <a class="pages__page {{ request('page') !== 'mylist' ? 'pages__page--active' : '' }}" href="{{ route('home') }}">
            おすすめ
        </a>
        <a class="pages__page {{ request('page') === 'mylist' ? 'pages__page--active' : '' }}" href="{{ route('home', ['page' => 'mylist']) }}">
            マイリスト
        </a>
    </div>
    <div class="items">
        @if($page === 'mylist')
            @if(auth()->check())
                @if($items->isEmpty())
                    <p class="no-items">マイリストに商品がありません</p>
                @else
                    @foreach($items as $item)
                        <div class="item-card">
                            <div class="item-card__image">
                                <a href="{{ route('item.show', ['item_id' => $item->id]) }}">
                                    <img alt="商品画像" class="item-card__image-img" src="{{ asset($item->img_url) }}">
                                </a>
                                @if ($item->is_sold)
                                    <div class="item-card__label"></div>
                                @endif
                            </div>
                            <div class="item-card__name">
                                {{ $item->name }}
                            </div>
                        </div>
                    @endforeach
                @endif
            @else
                <p class="not-authenticated">マイリストを表示するにはログインしてください</p>
            @endif
        @else
            @foreach($items as $item)
                <div class="item-card">
                    <div class="item-card__image">
                        <a href="{{ route('item.show', ['item_id' => $item->id]) }}">
                            <img alt="商品画像" class="item-card__image-img" src="{{ asset($item->img_url) }}">
                        </a>
                        @if ($item->is_sold)
                            <div class="item-card__label"></div>
                        @endif
                    </div>
                    <div class="item-card__name">
                        {{ $item->name }}
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</div>
@endsection