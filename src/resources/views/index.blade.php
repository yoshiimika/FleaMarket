@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
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
        @if($items->isEmpty())
            <p class="no-items">
                @if(request('page') === 'mylist')
                    {{ auth()->check() ? 'マイリストに商品がありません' : 'マイリストを表示するにはログインしてください' }}
                @else
                    商品が見つかりません
                @endif
            </p>
        @else
            @foreach($items as $item)
                <div class="item-card">
                    <div class="item-card__image">
                        <a href="{{ route('item.show', ['item_id' => $item->id]) }}">
                            <img
                                alt="{{ $item->name }}の画像"
                                class="item-card__image-img"
                                src="{{ asset($item->img_url) }}">
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