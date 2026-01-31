<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Product;
use App\Models\Category;

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
        $tab = $request->input('tab','recommend');
        if ($tab == 'mylist') {
            $query->whereHas('likedUsers', function ($q) {
                $q->where('user_id', '=', auth()->id());
            });
        }

        // 検索機能：キーワードが入力されている場合、商品名で部分一致検索
        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->where('name', 'like', "%$keyword%");
        }

        // ここで初めてDBに問い合わせを実行し、条件に合う商品を取得
        $products = $query->get();

        // ビューに商品データを渡して表示
        return view('products.index', compact('products', 'tab', 'keyword'));
    }

    public function show($id)
    {
        // 商品情報を取得（見つからない場合は404エラー）
        $product = Product::findOrFail($id);

        // コメント一覧を取得
        $comments = $product->comments;

        // カテゴリー一覧を取得
        $categories = $product->categories;

        // いいね件数を取得
        $likeCount = $product->likedUsers()->count();

        // ログインユーザーがいいね済みかどうかを判定
        $isLiked = $product->likedUsers()->where('user_id', '=', auth()->id())->exists();

        // ビューに全てのデータを渡す
        return view('products.show', compact('product', 'comments', 'categories', 'likeCount', 'isLiked'));
    }

    public function create(Request $request)
    {
        // 全カテゴリーを取得
        $categories = Category::all();

        // ビューへ渡す
        return view('products.create', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        // 画像を保存
        $path = $request->file('image_path')->store('products', 'public');

        // 必要な情報を集めて保存
        $product = Product::create([
            'user_id' => auth()->id(),
            'image_path' => $path,
            'name' => $request->get('name'),
            'brand' => $request->get('brand'),
            'description' => $request->get('description'),
            'condition' => $request->get('condition'),
            'price' => $request->get('price'),
        ]);

        // カテゴリーの関連付け
        $product->categories()->attach($request->get('category_id'));

        // viewの表示
        return redirect('/');
    }
}
