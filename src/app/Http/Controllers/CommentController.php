<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $id)
    {
        //ログインユーザーを取得
        $user = auth()->user();
        //コメントをcommentsテーブルに保存
        Comment::create([
            'user_id' => $user->id,
            'product_id' => $id,
            'content' => $request->content,
        ]);

        //商品詳細ページ（/item/{id}）にリダイレクト
        return redirect('/item/' . $id);

    }
}
