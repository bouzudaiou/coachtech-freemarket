<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{
    public function create($id)
    {
        $product = Product::find($id);
        $user = auth()->user();
        $address = session('address', [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        return view('orders.create', compact('product', 'user', 'address'));
    }

    public function edit($id)
    {
        // 商品情報を取得
        $product = Product::find($id);
        // ログインユーザーを取得
        $user = auth()->user();
        // セッションから住所を取得（なければプロフィールの住所）
        $address = session('address', [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        // 送付先変更画面のビューを返す
        return view('orders.edit', compact('product', 'address'));
    }

    public function update(AddressRequest $request, $id)
    {
        $address = [
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ];
        $request->session()->put('address', $address);

        return redirect('/purchase/' . $id);
    }

    public function store(PurchaseRequest $request, $id)
    {
        // 商品情報を取得
        $product = Product::find($id);

        // ログインユーザーを取得
        $user = auth()->user();

        // セッションから住所を取得（なければプロフィールの住所）
        $address = session('address', [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        try {
            if ($request->payment_method == 'コンビニ払い') {
                Order::create([
                    'user_id' => $user->id,
                    'product_id' => $id,
                    'postal_code' => $address['postal_code'],
                    'address' => $address['address'],
                    'building' => $address['building'] ?? '',
                    'payment_method' => $request->payment_method,
                ]);

                // 商品をis_sold = trueに更新
                $product->is_sold = true;
                $product->save();

                // セッションをクリア
                session()->forget('address');

                // 商品一覧にリダイレクト
                return redirect('/')->with('success', '購入が完了しました');
            } else {
                // Stripe初期化
                \Stripe\Stripe::setApiKey(config('stripe.secret_key'));

                // Checkout セッションを作成
                $session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'jpy',
                            'product_data' => [
                                'name' => $product->name,
                            ],
                            'unit_amount' => $product->price,
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('purchase.success', ['product_id' => $id]),
                    'cancel_url' => route('purchase.cancel', ['product_id' => $id]),
                    'metadata' => [
                        'user_id' => $user->id,
                        'product_id' => $id,
                        'postal_code' => $address['postal_code'],
                        'address' => $address['address'],
                        'building' => $address['building'] ?? '',
                        'payment_method' => $request->payment_method,
                    ],
                ]);

                // セッションIDとその他の情報をセッションに保存
                session([
                    'checkout_session_id' => $session->id,
                    'order_data' => [
                        'user_id' => $user->id,
                        'product_id' => $id,
                        'postal_code' => $address['postal_code'],
                        'address' => $address['address'],
                        'building' => $address['building'] ?? '',
                        'payment_method' => $request->payment_method,
                    ],
                ]);

                // StripeのCheckoutページへリダイレクト
                return redirect($session->url);
            }

        } catch (\Exception $e) {
            return redirect()->route('purchase.show', $id)->withErrors(['payment' => '決済に失敗しました:' . $e->getMessage()]);
        }
    }

    // 決済成功時の処理
    public function success(Request $request)
    {
        // セッションから注文データを取得
        $orderData = session('order_data');
        $productId = $request->query('product_id');

        if (!$orderData) {
            return redirect('/')->with('error', '注文データが見つかりません');
        }

        // 注文をデータベースに保存
        Order::create([
            'user_id' => $orderData['user_id'],
            'product_id' => $productId,
            'postal_code' => $orderData['postal_code'],
            'address' => $orderData['address'],
            'building' => $orderData['building'],
            'payment_method' => $orderData['payment_method'],
        ]);

        // 商品をis_sold = trueに更新
        $product = Product::find($productId);
        $product->is_sold = true;
        $product->save();

        // セッションをクリア
        session()->forget(['order_data', 'checkout_session_id', 'address']);

        // 商品一覧にリダイレクト
        return redirect('/')->with('success', '購入が完了しました');
    }

// 決済キャンセル時の処理
    public function cancel(Request $request)
    {
        $productId = $request->query('product_id');

        // セッションをクリア
        session()->forget(['order_data', 'checkout_session_id']);

        // 購入画面に戻る
        return redirect()->route('purchase.show', $productId)->with('error', '決済がキャンセルされました');
    }

}
