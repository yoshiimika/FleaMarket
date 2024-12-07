@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/sell/sell.css') }}">
<div class="sell-container">
    <h1 class="sell-title">
        商品の出品
    </h1>
    <form action="{{ route('sell.store') }}" class="sell-form" enctype="multipart/form-data" method="POST">
    @csrf
        <div class="sell-form__group">
            <label class="sell-form__label">商品画像</label>
            <div class="sell-form__image-upload">
                <label class="sell-form__image-button" for="image">画像を選択する</label>
                <input class="sell-form__image-input" id="image" name="image" type="file">
                <div class="sell-form__image-preview" style="display: none;">
                    <img alt="プレビュー画像" class="sell-form__image-preview-img" id="image-preview">
                </div>
            </div>
            @error('image')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="sell-form__group">
            <h2 class="sell-form__section-title">
                商品の詳細
            </h2>
            <label class="sell-form__label">カテゴリー</label>
            <div class="sell-form__categories">
                @foreach ($categories as $category)
                    <label class="sell-form__category {{ in_array($category->id, old('category_ids', [])) ? 'sell-form__category--active' : '' }}">
                        <input class="sell-form__category-input" name="category_ids[]" type="checkbox" value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>
            @error('category_ids')
                <span class="error__message">{{ $message }}</span>
            @enderror
            <label class="sell-form__label">ブランド</label>
            <select class="sell-form__select" id="brand-select" name="brand_id" {{ old('brand_id') ? '' : 'disabled' }}>
                <option value="" disabled {{ old('brand_id') ? '' : 'selected' }}>カテゴリーを選択してください</option>
                @if (old('brand_id'))
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            @error('brand_id')
                <span class="error__message">{{ $message }}</span>
            @enderror
            <label class="sell-form__label">カラー</label>
            <input class="sell-form__input" type="text" name="color" value="{{ old('color') }}">
            @error('color')
                <span class="error__message">{{ $message }}</span>
            @enderror
            <label class="sell-form__label">商品の状態</label>
            <select class="sell-form__select" name="condition">
                <option value="" disabled {{ old('condition') ? '' : 'selected' }}>選択してください</option>
                <option value="良好" {{ old('condition') == '良好' ? 'selected' : '' }}>良好</option>
                <option value="目立った傷や汚れなし" {{ old('condition') == '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                <option value="やや傷や汚れあり" {{ old('condition') == 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                <option value="状態が悪い" {{ old('condition') == '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
            </select>
            @error('condition')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="sell-form__group">
            <h2 class="sell-form__section-title">
                商品名と説明
            </h2>
            <label class="sell-form__label">商品名</label>
            <input class="sell-form__input" type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <span class="error__message">{{ $message }}</span>
            @enderror
            <label class="sell-form__label">商品の説明</label>
            <textarea class="sell-form__textarea" name="description" rows="5">{{ old('description') }}</textarea>
            @error('description')
                <span class="error__message">{{ $message }}</span>
            @enderror
            <label class="sell-form__label">販売価格</label>
            <div class="sell-form__price">
                <input class="sell-form__input sell-form__price-input" name="price" type="number" value="{{ old('price') }}" min="0">
                <span class="sell-form__price-symbol">¥</span>
            </div>
            @error('price')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="sell-form__group">
            <button class="sell-form__submit-button" type="submit">
                出品する
            </button>
        </div>
    </form>
</div>
<script src="{{ asset('js/image-preview.js') }}" defer></script>
<script src="{{ asset('js/category-brand-handler.js') }}" defer></script>
@endsection
