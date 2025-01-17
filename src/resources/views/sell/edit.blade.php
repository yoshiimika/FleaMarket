@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell/edit.css') }}">
@endsection

@section('content')
<div class="edit-container">
    <h1 class="edit-title">
        商品情報の編集
    </h1>
    <form action="{{ route('sell.update', $item->id) }}" class="edit-form" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PATCH')
        <div class="edit-form__group">
            <label class="edit-form__label">商品画像</label>
            <div class="edit-form__image-upload">
                <label class="edit-form__image-button" for="image">画像を変更する</label>
                <input class="edit-form__image-input" id="image" name="image" type="file">
                <div class="edit-form__image-preview">
                    @if ($item->img_url)
                        <img id="image-preview" alt="現在の画像" class="edit-form__image-preview-img" src="{{ asset($item->img_url) }}">
                    @endif
                </div>
            </div>
            @error('image')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="edit-form__group">
            <h2 class="edit-form__section-title">
                商品の詳細
            </h2>
            <label class="edit-form__label">カテゴリー</label>
            <div class="edit-form__categories">
                @foreach ($categories as $category)
                    <label class="edit-form__category {{ in_array($category->id, old('category_ids', $selectedCategories)) ? 'edit-form__category--active' : '' }}">
                        <input class="edit-form__category-input" name="category_ids[]" type="checkbox" value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', $selectedCategories)) ? 'checked' : '' }}>
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>
            @error('category_ids')
                <span class="error__message">{{ $message }}</span>
            @enderror
            <label class="edit-form__label">ブランド</label>
            <select class="edit-form__select" id="brand-select" name="brand_id">
                <option value="" {{ is_null($item->brand_id) ? 'selected' : '' }}>ブランドを選択してください</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $item->brand_id == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
            @error('brand_id')
                <span class="error__message">{{ $message }}</span>
            @enderror
            <label class="edit-form__label">カラー</label>
            <input class="edit-form__input" type="text" name="color" value="{{ old('color', $item->color) }}">
            @error('color')
                <span class="error__message">{{ $message }}</span>
            @enderror
            <label class="edit-form__label">商品の状態</label>
            <select class="edit-form__select" name="condition">
                <option value="" disabled>選択してください</option>
                <option value="良好" {{ old('condition', $item->condition) == '良好' ? 'selected' : '' }}>良好</option>
                <option value="目立った傷や汚れなし" {{ old('condition', $item->condition) == '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                <option value="やや傷や汚れあり" {{ old('condition', $item->condition) == 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                <option value="状態が悪い" {{ old('condition', $item->condition) == '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
            </select>
            @error('condition')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="edit-form__group">
            <h2 class="edit-form__section-title">
                商品名と説明
            </h2>
            <label class="edit-form__label">商品名</label>
            <input class="edit-form__input" type="text" name="name" value="{{ old('name', $item->name) }}">
            @error('name')
                <span class="error__message">{{ $message }}</span>
            @enderror
            <label class="edit-form__label">商品の説明</label>
            <textarea class="edit-form__textarea" name="description" rows="5">{{ old('description', $item->description) }}</textarea>
            @error('description')
                <span class="error__message">{{ $message }}</span>
            @enderror
            <label class="edit-form__label">販売価格</label>
            <div class="edit-form__price">
                <input class="edit-form__input edit-form__price-input" name="price" type="number" value="{{ old('price', $item->price) }}" min="0">
                <span class="edit-form__price-symbol">¥</span>
            </div>
            @error('price')
                <span class="error__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="edit-form__group">
            <button class="edit-form__submit-button" type="submit">
                商品情報を変更する
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/edit-image-preview.js') }}" defer></script>
<script src="{{ asset('js/edit-category-brand-handler.js') }}" defer></script>
@endsection
