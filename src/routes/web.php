<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 商品一覧（トップページ）
Route::get('/', [ProductController::class, 'index'])->name('products.index');

// 商品詳細
Route::get('/item/{id}', [ProductController::class, 'show'])->name('item.show');

// 認証必要（グループ化）
Route::middleware(['auth', 'verified'])->group(function () {
    // マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');

    // プロフィール編集画面
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.edit');

    // プロフィール更新
    Route::post('/mypage/profile', [MypageController::class, 'update'])->name('mypage.update');

    //いいね
    Route::post('/item/{id}/like', [LikeController::class, 'toggle'])->name('item.like');

    // Stripe決済成功・キャンセル（/purchase/{id} より前に定義する）
    Route::get('/purchase/success', [OrderController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/cancel', [OrderController::class, 'cancel'])->name('purchase.cancel');

    // 購入画面の表示
    Route::get('/purchase/{id}', [OrderController::class, 'create'])->name('purchase.show');

    //送付先住所入力画面
    Route::get('/purchase/address/{id}', [OrderController::class, 'edit'])->name('purchase.address');

    //送付先住所更新
    Route::post('/purchase/address/{id}', [OrderController::class, 'update'])->name('purchase.address.update');

    //購入処理
    Route::post('/purchase/{id}', [OrderController::class, 'store'])->name('purchase.process');

    //コメント投稿
    Route::post('/item/{id}/comment', [CommentController::class, 'store'])->name('item.comment');

    //出品情報入力画面の表示
    Route::get('/sell', [ProductController::class, 'create'])->name('products.create');

    //出品処理
    Route::post('/sell', [ProductController::class, 'store'])->name('products.store');
});
