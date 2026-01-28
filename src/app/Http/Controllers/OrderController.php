<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create($id)
    {
        $product = Product::find($id);
        $user = auth()->user();
        $address = session('address', $user->address);

        return view('orders.create', compact('product', 'user', 'address'));
    }

    public function edit($id)
    {
        // 商品情報を取得
        $product = Product::find($id);
        // ログインユーザーを取得
        $user = auth()->user();
        // セッションから住所を取得（なければプロフィールの住所）
        $address = session('address', $user->address);

        // 送付先変更画面のビューを返す
        return view('orders.edit', compact('product','address'));
    }

    public function update(Request $request, $id)
    {
        $address = $request->input('address');
        $request->session()->put('address', $address);

        return redirect('/purchase/' . $id);
    }

    public function store(Request $request, $id)
    {
        // 商品情報を取得
        $product = Product::find($id);
        // ログインユーザーを取得
        $user = auth()->user();
        // セッションから住所を取得
        $address = session('address', $user->address);
        // バリデーション
        //商品のis_soldをtrueに更新
        $product->is_sold = true;
        $product->save();
        // ここに購入処理を書く（Stripe連携など）
        // セッションを削除
        session()->forget('address');
        // リダイレクト
        return redirect('/');

    }
}
