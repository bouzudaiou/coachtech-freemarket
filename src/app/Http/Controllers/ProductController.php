<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // クエリビルダーを準備（まだDBにアクセスしていない）
        $query = Product::query();

        // ログイン判定：ログインしている場合、自分が出品した商品を除外
        if (auth()->check()) {
            $user = auth()->user();
            $query->where('user_id', '!=', $user->id);
        }

        // タブ切り替え：マイリストタブの場合、ログインユーザーがいいねした商品のみ表示
        $tab = $request->input('tab');
        if ($tab == 'mylist') {
            $query->whereHas('likes', function ($q) {
                $q->where('user_id', '=', auth()->id());
            });
        }

        // 検索機能：キーワードが入力されている場合、商品名で部分一致検索
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('name', 'like', "%{$keyword}%");
        }

        // ここで初めてDBに問い合わせを実行し、条件に合う商品を取得
        $products = $query->get();

        // ビューに商品データを渡して表示
        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        // 商品情報を取得
        $product = Product::find($id);

        // コメント一覧を取得
        $comments = $product->comments;

        // カテゴリー一覧を取得
        $categories = $product->categories;

        // いいね件数を取得
        $likeCount = $product->likes()->count();

        // ログインユーザーがいいね済みかどうかを判定
        $isLiked = $product->likes()->where('user_id', '=', auth()->id())->exists();

        // ビューに全てのデータを渡す
        return view('products.show', compact('product', 'comments', 'categories', 'likeCount', 'isLiked'));
    }

}
