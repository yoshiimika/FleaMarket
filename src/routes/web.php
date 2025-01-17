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

Route::get('/search', [ItemController::class, 'search'])
->name('item.search');

Route::prefix('item')->name('item.')->group(function () {
    Route::get('/{item_id}', [ItemController::class, 'show'])
    ->name('show');
    Route::middleware(['auth', 'verified'])->post('/{item_id}/favorite', [FavoriteController::class, 'toggle'])
    ->name('favorite');
    Route::middleware(['auth', 'verified'])->post('/{item_id}/comment', [CommentController::class, 'store'])
    ->name('comment.store');
});

Route::middleware(['auth', 'verified'])->prefix('purchase')->group(function () {
    Route::name('purchase.')->group(function () {
        Route::get('/{item_id}', [PurchaseController::class, 'showPurchaseForm'])
        ->name('show');
        Route::post('/{item_id}', [PurchaseController::class, 'purchase'])
        ->name('store');
        Route::get('/success/{item_id}', [PurchaseController::class, 'success'])
        ->name('success');
        Route::get('/cancel/{item_id}', [PurchaseController::class, 'cancel'])
        ->name('cancel');
    });
    Route::name('address.')->group(function () {
        Route::get('/address/{item_id}', [AddressController::class, 'editShoppingAddress'])
        ->name('edit');
        Route::put('/address/{item_id}', [AddressController::class, 'updateShoppingAddress'])
        ->name('update');
    });
});

Route::middleware(['auth', 'verified'])->prefix('sell')->name('sell.')->group(function () {
    Route::get('/', [ListingController::class, 'create'])
    ->name('create');
    Route::post('/', [ListingController::class, 'store'])
    ->name('store');
    Route::get('/{id}/edit', [ListingController::class, 'edit'])
    ->name('edit');
    Route::patch('/{id}/update', [ListingController::class, 'update'])
    ->name('update');
});

Route::middleware(['auth', 'verified'])->prefix('mypage')->name('profile.')->group(function () {
    Route::get('/', [UserController::class, 'showProfile'])
    ->name('show');
    Route::post('/profile', [UserController::class, 'createProfile'])
    ->name('create');
    Route::get('/profile', [UserController::class, 'editProfile'])
    ->name('edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])
    ->name('update');
});
