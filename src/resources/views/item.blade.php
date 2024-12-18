@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item-container">
    <div class="item-image">
        <div class="item-image__placeholder">
            <img alt="{{ $item->name }}の画像" class="item-image__img" src="{{ asset($item->img_url) }}">
            @if ($item->is_sold)
                <div class="item-label">
                    <span class="item-text">SOLD</span>
                </div>
            @endif
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
        <div class="item-icons">
            <div class="item-icons__icon">
                <form action="{{ route('item.favorite', $item->id) }}" class="favorite-form" method="POST">
                @csrf
                    <button class="favorite-button" type="submit">
                        @if($item->is_favorite)
                            <span class="favorite-icon active">★</span>
                        @else
                            <span class="favorite-icon">☆</span>
                        @endif
                    </button>
                </form>
                <span class="item-icons__count">{{ $item->favorites_count }}</span>
            </div>
            <div class="item-icons__icon">
                <img alt="コメント" class="item-icons__img" src="{{ asset('img/comment-icon.svg') }}">
                <span class="item-icons__count">{{ $item->comments_count }}</span>
            </div>
        </div>
        <a class="item-button__purchase {{ $item->is_sold || auth()->id() === $item->user_id ? 'item-button__purchase--disabled' : '' }}"
            href="{{ !$item->is_sold && auth()->id() !== $item->user_id ? route('purchase.show', $item->id) : '#' }}">
            @if (auth()->id() === $item->user_id)
                出品者は購入できません
            @elseif ($item->is_sold)
                この商品は売り切れです
            @else
                購入手続きへ
            @endif
        </a>

        <div class="item-description">
            <h2 class="item-description__title">
                商品説明
            </h2>
            <p class="item-description__content">
                カラー：{{ $item->color ?? '指定なし' }}
            </p>
            <p class="item-description__content">
                {!! nl2br(e($item->description)) !!}
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
                コメント ({{ $item->comments_count }})
            </h2>
            @foreach($item->comments as $comment)
                <div class="comment">
                    <div class="comment-header">
                        <div class="comment-avatar">
                            @if ($comment->user->img_url)
                                <img class="comment-avatar__img" src="{{ asset('storage/' . $comment->user->img_url) }}">
                            @endif
                        </div>
                        <strong class="comment-user">{{ $comment->user->name }}</strong>
                    </div>
                    <div class="comment-content">
                        <p class="comment-content__text">{!! nl2br(e($comment->content)) !!}</p>
                    </div>
                </div>
            @endforeach
            <form action="{{ route('item.comment.store', $item->id) }}" class="comment-form" method="POST">
            @csrf
                <label class="comment-form__label" for="comment">商品へのコメント</label>
                <textarea class="comment-form__textarea" id="comment" name="content"></textarea>
                @error('content')
                    <span class="error__message">{{ $message }}</span>
                @enderror
                @if (!$item->is_sold)
                    <button class="comment-form__button" type="submit">
                        コメントを送信する
                    </button>
                @else
                    <button class="comment-form__button comment-form__button--disabled" type="button" disabled>
                        この商品は売り切れのためコメントできません
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection