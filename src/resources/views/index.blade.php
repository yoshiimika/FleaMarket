@extends('layouts.app')

@section('content')
<link href="{{ asset('css/index.css') }}" rel="stylesheet">
<div class="container">
    <div class="tabs">
        <a class="tabs__tab {{ request('tab') !== 'mylist' ? 'tabs__tab--active' : '' }}" href="{{ route('home') }}">
            おすすめ
        </a>
        <a class="tabs__tab {{ request('tab') === 'mylist' ? 'tabs__tab--active' : '' }}" href="{{ route('home', ['tab' => 'mylist']) }}">
            マイリスト
        </a>
    </div>
    <div class="items">
        @if($tab === 'mylist')
            @if(auth()->check())
                @if($items->isEmpty())
                    <p class="no-items">マイリストに商品がありません。</p>
                @else
                    <div class="items">
                        @foreach($items as $item)
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
        @else
            @foreach($items as $item)
                <div class="item-card">
                    <div class="item-card__image">
                        <a href="{{ route('item.show', ['item_id' => $item->id]) }}">
                            <img alt="商品画像" class="item-card__image-img" src="{{ asset($item->img_url) }}">
                        </a>
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