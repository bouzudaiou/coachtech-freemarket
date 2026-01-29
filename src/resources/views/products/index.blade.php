@extends('layouts.app')

@section('title', '商品一覧 - COACHTECH')

@section('content')
    <style>
        /* タブ切り替え */
        .tabs {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 16px;
            color: #666;
        }

        .tab.active {
            color: #000;
            font-weight: bold;
            border-bottom: 3px solid #ff0000;
            margin-bottom: -2px;
        }

        /* 商品グリッド */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
        }

        .product-card {
            text-decoration: none;
            color: #333;
            position: relative;
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            background-color: #f0f0f0;
        }

        .product-info {
            padding: 10px 0;
        }

        .product-name {
            font-size: 14px;
            margin-bottom: 5px;
        }

        /* SOLD表示 */
        .sold-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #ff0000;
            color: #fff;
            padding: 5px 15px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>

    <!-- タブ切り替え （ログイン時のみ表示）-->
    @auth
    <div class="tabs">
        <button class="tab {{ $page === 'all' ? 'active' : '' }}" onclick="location.href='/?page=all'">
            おすすめ
        </button>
        <button class="tab {{ $page === 'mylist' ? 'active' : '' }}" onclick="location.href='/?page=mylist'">
            マイリスト
        </button>
    </div>
    @endauth

    <!-- 商品一覧 -->
    <div class="products-grid">
        @forelse($products as $product)
            <a href="/item/{{ $product->id }}" class="product-card">
                @if($product->is_sold)
                    <div class="sold-label">Sold</div>
                @endif

                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="product-image">

                <div class="product-info">
                    <div class="product-name">{{ $product->name }}</div>
                </div>
            </a>
        @empty
            <p>商品がありません</p>
        @endforelse
    </div>
@endsection
