<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        // ログインユーザー情報を取得
        $user = auth()->user();

        // クエリパラメーターを取得
        $page = $request->query('page');

        if ($page === 'buy') {
            // 購入した商品一覧
            $orders = $user->orders;
            $products = [];
            foreach ($orders as $order) {
                $products[] = $order->product;
            }
        } else {
            // 出品した商品一覧（デフォルト）
            $products = $user->products;
        }

        return view('mypage.index', compact('user', 'products', 'page'));
    }

    public function update(ProfileRequest $request)
    {
        // ログインユーザー情報を取得
        $user = auth()->user();

        // 画像がアップロードされた場合は保存、なければ既存のパスを使う
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
        } else {
            $path = $user->profile_image_path;
        }

        // ユーザー情報を更新
        $user->update([
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
            'profile_image_path' => $path,
            'is_profile_completed' => 1,
        ]);

        // マイページにリダイレクト
        return redirect('/');
    }

    public function edit()
    {
        // ログインユーザー情報を取得
        $user = auth()->user();

        //プロフィール設定画面への遷移
        return view('mypage.edit', compact('user'));
    }
}
