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

Route::get('/', [ItemController::class, 'index'])
->name('home');
Route::get('/mylist', [ItemController::class, 'myList'])
->name('mylist');

Route::get('/search', [ItemController::class, 'search'])
->name('item.search');

// 商品関連のルーティング
Route::prefix('item')->group(function () {
    Route::get('/{item_id}', [ItemController::class, 'show'])
    ->name('item.show');
    // いいね機能（お気に入り登録/解除）
    Route::middleware('auth')->post('/{item_id}/favorite', [FavoriteController::class, 'toggle'])
    ->name('item.favorite');
    Route::middleware('auth')->post('/{item_id}/comment', [CommentController::class, 'store'])
    ->name('item.comment.store');
});

// 購入関連のルーティング
Route::middleware('auth')->prefix('purchase')->group(function () {
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
    ->name('item.upload_image');
});

// マイページ関連のルーティング
Route::prefix('mypage')->group(function () {
    Route::get('/', [UserController::class, 'showProfile'])
    ->name('profile');
    Route::get('/profile', [UserController::class, 'editProfile'])
    ->name('profile.edit');
    Route::post('/profile', [UserController::class, 'updateProfile'])
    ->name('profile.update');
    Route::get('/?page=buy', [UserController::class, 'showPurchasedItems'])
    ->name('profile.purchased');
    Route::get('/?page=sell', [UserController::class, 'showSoldItems'])
    ->name('profile.sold');
});