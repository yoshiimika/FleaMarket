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
    ->middleware('verified.query')
    ->name('home');

Route::get('/search', [ItemController::class, 'search'])
->name('item.search');

Route::prefix('item')->group(function () {
    Route::get('/{item_id}', [ItemController::class, 'show'])
    ->name('item.show');
    Route::middleware(['auth', 'verified'])->post('/{item_id}/favorite', [FavoriteController::class, 'toggle'])
    ->name('item.favorite');
    Route::middleware(['auth', 'verified'])->post('/{item_id}/comment', [CommentController::class, 'store'])
    ->name('item.comment.store');
});

Route::middleware(['auth', 'verified'])->prefix('purchase')->group(function () {
    Route::get('/{item_id}', [PurchaseController::class, 'showPurchaseForm'])
    ->name('purchase.show');
    Route::post('/{item_id}', [PurchaseController::class, 'purchase'])
    ->name('purchase');
    Route::get('/success/{item_id}', [PurchaseController::class, 'success'])
    ->name('purchase.success');
    Route::get('/cancel/{item_id}', [PurchaseController::class, 'cancel'])
    ->name('purchase.cancel');
    Route::get('/address/{item_id}', [AddressController::class, 'editShoppingAddress'])
    ->name('address.edit');
    Route::put('/address/{item_id}', [AddressController::class, 'updateShoppingAddress'])
    ->name('address.update');
});

Route::middleware(['auth', 'verified'])->prefix('sell')->group(function () {
    Route::get('/', [ListingController::class, 'create'])
    ->name('sell');
    Route::post('/', [ListingController::class, 'store'])
    ->name('sell.store');
});

Route::middleware(['auth', 'verified'])->prefix('mypage')->group(function () {
    Route::get('/', [UserController::class, 'showProfile'])
    ->name('profile');
    Route::get('/profile', [UserController::class, 'editProfile'])
    ->name('profile.edit');
    Route::post('/profile', [UserController::class, 'createProfile'])
    ->name('profile.create');
    Route::put('/profile', [UserController::class, 'updateProfile'])
    ->name('profile.update');
});
