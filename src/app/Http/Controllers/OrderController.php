<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Charge;
use App\Http\Requests\PurchaseRequest;
use App\Models\Order;
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

    public function store(PurchaseRequest $request, $id)
    {
        // 商品情報を取得
        $product = Product::find($id);

        // ログインユーザーを取得
        $user = auth()->user();

        // セッションから住所を取得
        $address = session('address', $user->address);

        // Stripe決済処理
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $charge = Charge::create([
                'amount' => $product->price,
                'currency' => 'jpy',
                'source' => 'tok_visa', // テストカードトークン
                'description' => $product->name . 'の購入',
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['payment' => '決済に失敗しました']);
        }

        // 決済成功後、注文データを保存
        Order::create([
            'user_id' => $user->id,
            'product_id' => $id,
            'address' => $address,
            'payment_method' => $request->payment_method,
        ]);

        // 商品のis_soldをtrueに更新
        $product->is_sold = true;
        $product->save();

        // セッションを削除
        session()->forget('address');

        // リダイレクト
        return redirect('/');
    }

}
