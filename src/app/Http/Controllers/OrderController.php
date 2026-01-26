<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create($id)
    {
        $product = Product::find($id);
        $user = auth()->user;

        return view(  );
    }
}
