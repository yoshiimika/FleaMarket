<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ホームページと商品一覧
Route::get('/', [ItemController::class, 'index'])
->name('home');
Route::get('/?page=mylist', [ItemController::class, 'myList'])
->name('mylist');

// 商品検索機能
Route::get('/search', [ItemController::class, 'search'])
->name('product.search');

// 商品関連のルーティング
Route::prefix('item')->group(function () {
    Route::get('/{item_id}', [ItemController::class, 'show'])
    ->name('product.show');
    // いいね機能（お気に入り登録/解除）
    Route::post('/{item_id}/favorite', [FavoriteController::class, 'toggle'])
    ->name('product.favorite');
    // コメント機能
    Route::post('/{item_id}/comment', [CommentController::class, 'store'])
    ->name('product.comment.store');
});

// 購入関連のルーティング
Route::prefix('purchase')->group(function () {
    Route::get('/{item_id}', [PurchaseController::class, 'showPurchaseForm'])
    ->name('purchase.show');
    Route::post('/{item_id}', [PurchaseController::class, 'purchase']);
    Route::get('/address/{item_id}', [AddressController::class, 'editAddress'])
    ->name('address.edit');
    Route::post('/address/{item_id}', [AddressController::class, 'updateAddress']);
});

// 出品関連のルーティング
Route::prefix('sell')->group(function () {
    Route::get('/', [ListingController::class, 'create'])
    ->name('sell');
    Route::post('/', [ListingController::class, 'store'])
    ->name('sell.store');
    // 商品画像アップロード機能
    Route::post('/{item_id}/upload-image', [ListingController::class, 'uploadImage'])
    ->name('product.upload_image');
});

// マイページ関連のルーティング
Route::prefix('mypage')->group(function () {
    Route::get('/', [UserController::class, 'showProfile'])
    ->name('profile');
    Route::get('/profile', [UserController::class, 'editProfile'])
    ->name('profile.edit');
    Route::post('/profile', [UserController::class, 'updateProfile']);
    Route::get('/?page=buy', [UserController::class, 'showPurchasedItems'])
    ->name('profile.purchased');
    Route::get('/?page=sell', [UserController::class, 'showSoldItems'])
    ->name('profile.sold');
});