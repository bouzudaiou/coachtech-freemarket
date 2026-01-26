<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MypageController;

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
