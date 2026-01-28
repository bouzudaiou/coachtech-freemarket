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
Route::get('/', [ProductController::class, 'index']);

// 商品詳細
Route::get('/item/{id}', [ProductController::class, 'show']);

// マイページ
Route::get('/mypage', [MypageController::class, 'index']);

// プロフィール編集画面
Route::get('/mypage/profile', [MypageController::class, 'edit']);

// プロフィール更新
Route::post('/mypage/profile', [MypageController::class, 'update']);

//いいね
Route::post('/item/{id}/like', [LikeController::class, 'toggle']);

// 購入画面の表示
Route::get('/purchase/{id}', [OrderController::class, 'create']);

//送付先住所入力画面
Route::get('/purchase/address/{id}', [OrderController::class, 'edit']);

//送付先住所更新
Route::post('/purchase/address/{id}', [OrderController::class, 'update']);

//購入処理
Route::post('/purchase/{id}', [OrderController::class, 'store']);

//コメント投稿
Route::post('/item/{id}/comment', [CommentController::class, 'store']);

//出品情報入力画面の表示
Route::get('/sell', [ProductController::class, 'create'])->name('products.create');

//出品処理
Route::post('/sell', [ProductController::class, 'store'])->name('products.store');
