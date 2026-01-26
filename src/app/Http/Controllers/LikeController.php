<?php

namespace App\Http\Controllers;

use App\Models\Product;

class LikeController extends Controller
{
    public function toggle($id)
    {
        $product = Product::findOrFail($id);

        $isLiked = $product->likedUsers()->where('user_id', '=', auth()->id())->exists();

        if ($isLiked) {
            $product->likedUsers()->detach(auth()->id());
        } else {
            $product->likedUsers()->attach(auth()->id());
        }

        return redirect('/item/' . $id);
    }
}
