@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">

<div class="item-container">
    <div class="item-image">
        <div class="item-image__placeholder">
            <img alt="商品画像" class="item-image__img" src="{{ asset($item->img_url) }}">
        </div>
    </div>
    <div class="item-details">
        <h1 class="item-title">
            {{ $item->name }}
        </h1>
        <p class="item-brand">
            {{ $item->brand->name ?? 'ブランド情報なし' }}
        </p>
        <p class="item-price">
            ¥{{ number_format($item->price) }}
            <span class="item-tax">(税込)</span>
        </p>
        <a class="item-button--purchase" href="{{ route('purchase.show', $item->id) }}">
            購入手続きへ
        </a>

        <div class="item-description">
            <h2 class="item-description__title">
                商品説明
            </h2>
            <p class="item-description__content">
                {{ $item->description }}
            </p>
        </div>

        <div class="item-info">
            <h2 class="item-info__title">
                商品の情報
            </h2>
            <div class="item-info__categories">
                <p class="item-info__label">
                    カテゴリー
                </p>
                <div class="item-info__tags">
                    @foreach ($item->categories as $category)
                        <span class="item-tag">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>
            <div class="item-info__condition">
                <p class="item-info__label">
                    商品の状態
                </p>
                <p class="item-info__value">
                    {{ $item->condition }}
                </p>
            </div>
        </div>

        <div class="item-comments">
            <h2 class="item-comments__title">
                コメント ({{ $comments->count() }})
            </h2>
            @foreach($comments as $comment)
                <div class="comment">
                    <div class="comment-avatar">🟢</div>
                    <div class="comment-content">
                        <strong>{{ $comment->user->name }}</strong>
                        <p class="comment-content__text">{{ $comment->content }}</p>
                    </div>
                </div>
            @endforeach

            <form action="{{ route('item.comment.store', $item->id) }}" class="comment-form" method="POST">
            @csrf
                <label for="comment">商品へのコメント</label>
                <textarea class="comment-form__textarea" id="comment" name="content"></textarea>
                <button class="comment-form__button" type="submit">
                    コメントを送信する
                </button>
            </form>
        </div>
    </div>
</div>
@endsection